## About The Project

A small Laravel application that fetches a few pieces of data from the free/public Github API about the status of pull requests on a repository. This tool would help  proactively monitor code repositories through automation.

### Built With

- <a href="https://www.laravel.com/"> <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/> </a>

### How It Works

- All methods call the GitHub API's pulls method. The Github API is made with pagination and can only display only a maximum of 100 data items per call. The way the data is fetched is by looping through fetching data from the API on each iteration until we receive an empty array response, in which there are no more data to fetch, so the loop terminates.

- The old method gets all pull requests that are older than 14 days. In each iteration of the loop, we loop over the Github API response and check if the created at attribute of the individual request and current time difference amounts to more than 14 days, and if so the request is pushed to the method's response array and appended to the corresponding file.

- The review method gets all pull requests with review required. In each iteration of the loop, we loop over the Github API response and check if the requested_reviewers or requested_teams attributes of the individual request are non-empty arrays, and if so the request is pushed to the method's response array and appended to the corresponding file.

- The status method gets all pull requests with status success. In each iteration of the loop, we loop over the Github API response and call the Github API's commits method with the merge_commit_sha attribute of the individual request to identify the commit checking if the status/state attribute of the request from the API call equals to success, and if so the request is pushed to the method's response array and appended to the corresponding file.

- The status method gets all pull requests with status success. In each iteration of the loop, we loop over the Github API response and call the Github API's commits method with the merge_commit_sha attribute of the individual request to identify the commit checking if the status/state attribute of the request from the API call equals to success, and if so the request is pushed to the method's response array and appended to the corresponding file.

- The unassigned method gets all pull requests with no assigned reviewers. In each iteration of the loop, we loop over the Github API response and check if the assignee and assignees attributes of the individual request are not null and a non-empty array respectively, and if so the request is pushed to the method's response array and appended to the corresponding file.
