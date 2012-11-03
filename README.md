# hubcap
## documentation publishing automation for github

I love Github. You love Github. The only problem is that most of us who use Github's pages feature either currently manually update the gh-pages branch with our documentation or we wish that we had that kind of time. Enter Hubcap.

Hubcap simplifies your life and mine by making documentation updates automatic. Shortly after you push changes to Github, we get a notification via a post-receive hook. In accordance with your settings we then deploy the latest version of your docs! See? I told you it was simple.

Learn more at http://hubcap.it/.

## contributing

### pagoda box
This web application is designed to be deployed to a Pagoda Box repository.  Creating a test repository at Pagoda Box will make development easier and is encouraged before pull requests are submitted.  Due the the unique nature of their hosting environment, some code that may operate fine on a development server does not always run successfully at Pagoda Box.

### git-flow
We use the git-flow model to structure our branches. This means you won't see a lot of activity on the master branch besides releases. Most of the magic happens in the develop branch.
[More about git-flow](http://nvie.com/posts/a-successful-git-branching-model/)

-----------------

Hubcap is a free service of [Brodkin CyberArts](http://brodkinca.com/).
