<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PullRequestsController extends Controller
{
  public function curl($url, $headers){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = json_decode(curl_exec($ch));
    return $result;
  }

  public function old(){
    $old_requests = [];
    for ($page_number = 1; $page_number < 2; $page_number++){
      $url = 'https://api.github.com/repos/woocommerce/woocommerce/pulls?state=open&per_page=30&page='.$page_number;
      $headers = ([
        'Accept:application/vnd.github+json',
        'User-Agent:mohammad-kasem'
      ]);

      $requests = $this->curl($url, $headers);
      if (!count($requests)) {
        break;
      }
      foreach ($requests as $request) {
        $request_time = strtotime($request->created_at);
        $current_time = strtotime(date('Y-m-d H:i'));
        if ((($current_time - $request_time) / 86400) > 14){
          $old_request = ([
            'id' => $request->id,
            'title' => $request->title,
            'url' => $request->url,
            'user' => $request->user->login,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
          ]);
          array_push($old_requests, $old_request);
          $request_imploded = implode(",", $old_request);
          file_put_contents('old-pull-requests.txt', "\n".$request_imploded,FILE_APPEND);
        }
      }
    }
    return response()->json([
      'requests' => $old_requests
    ], 200);
  }

  public function review(){
    $review_requests = [];
    $page_number = 1;
    while(true){
      $url = 'https://api.github.com/repos/woocommerce/woocommerce/pulls?state=open&per_page=30&page='.$page_number;
      $headers = ([
        'Accept:application/vnd.github+json',
        'User-Agent:mohammad-kassem'
      ]);

      $requests = $this->curl($url, $headers);

      if ($requests and !count($requests)) {
        break;
      }

      foreach ($requests as $request) {
        if (!count($request->requested_reviewers) or !count($request->requested_teams)){
          $review_request = ([
            'id' => $request->id,
            'title' => $request->title,
            'url' => $request->url,
            'user' => $request->user->login,
            'created_at' => $request->created_at,
          ]);
        array_push($review_requests, $review_request);
        $request_imploded = implode(",", $review_request);
        file_put_contents('review-required-pull-requests.txt', "\n".$request_imploded,FILE_APPEND);
        }
      }
      $page_number++;
    }
    return response()->json([
      'requests' => $review_requests
    ], 200);  
  }

  public function status(){
    $status_requests = [];
    $page_number = 1;
    while(true){
      $url = 'https://api.github.com/repos/woocommerce/woocommerce/pulls?state=open&per_page=30&page='.$page_number;
      $headers = ([
        'Accept:application/vnd.github+json',
        'User-Agent:mohammad-kassem'
      ]);

      $requests = $this->curl($url, $headers);

      if ($requests and !count($requests)) {
        break;
      }

      foreach ($requests as $request) {
        $status_url = 'https://api.github.com/repos/woocommerce/woocommerce/commits/'.$request->merge_commit_sha.'/status';
        $status = $this->curl($status_url, $headers)->state;
        if ($status == "success"){
          $status_request = ([
            'id' => $request->id,
            'title' => $request->title,
            'url' => $request->url,
            'user' => $request->user->login,
            'created_at' => $request->created_at,
          ]);
        array_push($status_requests, $status_request);
        $request_imploded = implode(",", $status_request);
        file_put_contents('status-success-pull-requests.txt', "\n".$request_imploded,FILE_APPEND);
        }
      }
      $page_number++;
    }
    return response()->json([
      'requests' => $status_requests
    ], 200);
  }

}
