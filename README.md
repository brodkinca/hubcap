# Pagoda Box Quickstart for Codeigniter

**NOTE:** This repository should not be deployed on any host other than Pagoda Box.  Key application components will be missing unless the Pagoda Box hooks have already been executed.

## Getting Started: Pulling From Pagoda Box

### The Easy Way

Because this quickstart utilizes [Git submodules](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) a simple git clone isn't the easiest way to pull down the source.  Instead, issue the following command:

`git clone --recursive <repo_url>`

### The Hard Way

If you already rushed into things and did a *git pull* you can do one of two things:

1. Delete the repository from your computer and pull it down from Pagoda Box again using the "easy" instructions.
2. Issue the following commands from your repository root and make it all better:

`git submodule init
git submodule update`

## Why Submodules?

This quickstart is similar to other quickstarts for Codeigniter except that it comes bundled with Codeigniter as submodule.  If you are unfamiliar with submodules in Git you should refer to the [manual page](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) on submodules.  The benefit of loading Codeigniter as a submodule is that Codeigniter's source is maintained in its own repository and your application's repository contains nothing other than the application's source code and a pointer to which version of Codeigniter you're using.

## Upgrading Codeigniter

When you want to pull down the latest version of codeigniter, just navigate to the hidden *.codeigniter* directory and issue the following command:

`git pull origin master`

At this point you have the latest version of Codeigniter and can test it in your local development environment.

But wait, we're not done yet! To continue using this new Codeigniter version you have to tell your local repository that you've switched versions.  To do so simply navigate back to your main repository and issue this command:

`git commit .codeigniter -m 'Updated Codeigniter'`

From now on your application will deploy with the latest version!