<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>hubcap &middot; automatic github pages updater</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="/assets/dist/css/bootstrap.css">
        <link rel="stylesheet" href="/assets/dist/css/style.css">
        <link rel="stylesheet" href="/assets/lib/highlightjs/styles/github.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body data-spy="scroll" data-offset="80">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo site_url()?>">
                        hubcap
                        <span class="label label-inverse">BETA</span>
                    </a>
                    <div class="nav-collapse collapse">
                        <ul id="nav_main" class="nav">
                            <li class="active"><a href="#top">Home</a></li>
                            <?php if (isset($user['hubcap_id'])) : ?>
                            <li class="active"><a href="#my_repos">My Repos</a></li>
                            <?php else: ?>
                            <li><a href="#why">About</a></li>
                            <li><a href="#how">How It Works</a></li>
                            <?php endif; ?>
                            <li><a href="#advanced">Confguration</a></li>
                            <li><a href="http://github.com/brodkinca/hubcap">Contribute</a></li>
                            <li><a href="https://brodkinca.zendesk.com/forums/21456876-hubcap">Support</a></li>
                        </ul>
                        <?php if (isset($user['hubcap_id'])) : ?>
                        <ul id="user" class="nav pull-right">
                            <li>
                                <a href="http://github.com/<?php echo $user['github_login'] ?>">
                                    <img src="<?php echo $user['avatar'] ?>" class="img-rounded">
                                    <?php echo $user['github_login'] ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('main/logout') ?>">
                                    Logout
                                </a>
                            </li>
                        </ul>
                        <?php else: ?>
                        <div class="pull-right hidden-phone">
                            <a href="<?php echo site_url('main/login') ?>" class="btn btn-small">
                                <i class="icon-github"></i>
                                Login via Github
                            </a>
                        </div>
                        <?php endif; ?>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <?php if (isset($user['hubcap_id'])) : ?>

            <section id="top" class="hero-unit hidden-phone">
              <h1>Ahoy, <?php echo $user['github_login'] ?>!</h1>
              <p>Welcome back! Long time no see... How are the kids? Good. Good.</p>
            </section>

            <section id="my_repos" class="row">

                <div class="span12">
                    <div class="alert alert-error">
                        <strong>Warning!</strong> We're still polishing this girl up, but feel free to sign up, and get your settings just right.  We'll start updating your docs once we actually launch!
                    </div>
                </div>

                <div class="span12">
                    <h1>My Repositories</h1>
                </div>

                    <div id="target" class="span9">
                        <div class="progress progress-striped active">
                            <div class="bar" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="span3">
                        <p>After you push new code to Github, they notify us and we check for a hubcap configuration file in your document root.  If no config file is found we simply try to locate the docs directory.  We then copy your docs to gitbub pages and voila... updated documentation!</p>
                    </div>

            </section>

            <hr>

            <?php else: ?>

            <section id="top" class="hero-unit hidden-phone">

                <div id="banner">
                    <a href="http://github.com/brodkinca/hubcap">
                        Fork me on GitHub
                    </a>
                </div>
                <h1>
                    Hello, my name is hubcap!
                    <small>/ˈhəbˌkap/</small>
                </h1>
                <h2>I&apos;m the new way to keep your docs and Github pages in sync.</h2>
                <p>Once you connect me with your Github repositories I&apos;ll automatically fill your project&apos;s gh-pages branch with the latest copy of the docs.  That&apos;s it!  It really is that simple.  What are you waiting for?</p>
                <p><a href="<?php echo site_url('main/login') ?>" class="btn btn-info btn-large">Let's get going!</a></p>

            </section>

            <hr class="hidden-phone">

            <section id="why" class="row">

                <header class="span12">
                    <h1>
                        Why hubcap?
                        <small>
                            Documentation publishing automation for Github.
                        </small>
                    </h1>
                </header>
                <div class="span8">
                    <p>I love Github. You love Github.  The only problem is that most of us who use Github's pages feature either currently manually update the <code>gh-pages</code> branch with our documentation or we wish that we had that kind of time.  Enter Hubcap.</p>
                    <p>Hubcap simplifies your life and mine by making documentation updates automatic.  Shortly after you push changes to Github, we get a notification via a post-receive hook.  In accordance with your settings we then deploy the latest version of you docs!  See? I told you it was simple.</p>
                    <p><a class="btn" href="#">Get Started &raquo;</a></p>
                </div>
                <div class="span4 hidden-phone">
                    <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-5600676303459847";
                        /* Hubcap Right Above Fold */
                        google_ad_slot = "4304181416";
                        google_ad_width = 250;
                        google_ad_height = 250;
                        //-->
                    </script>
                    <script src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script>
               </div>
               <div class="span4 visible-phone">
                    <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-5600676303459847";
                        /* Hubcap Right Above Fold-Phone */
                        google_ad_slot = "8521548675";
                        google_ad_width = 320;
                        google_ad_height = 50;
                        //-->
                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
               </div>
            </section>

            <hr>

            <section id="how" class="row">

                <header class="span12">
                    <h1>
                        How does it work?
                        <small>It's super simple...</small>
                    </h1>
                </header>
                <div class="span4">
                    <h2>1. Code</h2>
                    <p>Write your code and compile your documents as you always do.  Commit the latest copy of your documents in any valid format (HTML, Markdown, etc.) to your docs directory.</p>
                </div>
                <div class="span4">
                    <h2>2. Push</h2>
                    <p>Push your code changes and updated documents to any repository on Github that has already been activated in Hubcap.</p>
                    <p>Optionally include a <a href="#advanced">Hubcap config file.</a></p>
               </div>
                <div class="span4">
                    <h2>3. Magic</h2>
                    <p>We handle the rest!  Sit back and relax!</p>
                    <p>Once Github notifies us that you've made changes, we'll queue your request and create a commit containing your updated docs.</p>
                </div>

            </section>

            <hr>

            <section class="row">
                <div id="call_to_action" class="span12">
                    <p>You still haven't signed in? Are you ready?</p>
                    <a href="<?php echo site_url('main/login') ?>" class="btn btn-info btn-large">
                        Yeah baby... Let's do this thing!
                    </a>
                </div>
            </section>
            <br>
            <?php endif; ?>

            <section id="advanced" class="row">
                <header class="span12">
                    <h1>
                        Advanced Usage
                        <small>Configuration Files</small>
                    </h1>
                </header>
                <div class="span8">
                    <p>Since most repositories have a lovely directory in the root of their repository called <code>/docs</code> we will simply look there for your beloved project documentation.  That said, we understand that one size does not fit all, and that is why we have configuration files!</p>
                    <p>The following is a very basic configuration file.  When we find a file named <code>hubcap.json</code> in the active branch of your project, we will parse it and use the data we find to override our default settings.</p>
                    <p><span class="label label-inverse">NOTE</span> At this time we only support a limited number of parameters.</p>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Description</th>
                                <th>Default&nbsp;Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>dest_branch</code></td>
                                <td>We have no idea why you'd want to do this, but if you want Hubcap to deploy your docs to a branch other than <code>gh-pages</code> you can set the name of that branch here.</td>
                                <td><code>gh-pages</code></td>
                            </tr>
                            <tr>
                                <td><code>dest_path</code></td>
                                <td>Path from the root of the repository to the directory where the docs should be stored.  This could be used, for instance, to automatically update docs in a subdirectory and manually maintain a website in the document root.</td>
                                <td><code>/</code></td>
                            </tr>
                            <tr>
                                <td><code>source_path</code></td>
                                <td>Path from the root directory of the repository to the documents directory.</td>
                                <td><code>/docs</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="span4">
                    <h2>simple config</h2>
                    <pre><code>{
    "source_path": "doc"
}</code></pre>
                    <h2>complex config</h2>
                    <pre><code>{
    "dest_branch": "website",
    "dest_path": "./docs",
    "source_path": "./docs/export"
}</code></pre>
                </div>
            </section>

            <div id="snarkatron" class="well well-small">
                There are currently <strong><?php echo number_format($count_queue)?></strong> pending jobs in the queue.
            </div>

            <footer class="row">
                <div class="span12 visible-desktop">
                    <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-5600676303459847";
                        /* Footer */
                        google_ad_slot = "6609763878";
                        google_ad_width = 728;
                        google_ad_height = 90;
                        //-->
                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                </div>
                <div class="span12 visible-tablet">
                    <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-5600676303459847";
                        /* Footer-Tablet */
                        google_ad_slot = "4091349073";
                        google_ad_width = 468;
                        google_ad_height = 60;
                        //-->
                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                </div>
                <div class="span12 visible-phone">
                    <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-5600676303459847";
                        /* Footer-Phone */
                        google_ad_slot = "7044815474";
                        google_ad_width = 320;
                        google_ad_height = 50;
                        //-->
                    </script>
                    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                </div>
                <p class="span6">
                    Hubcap is a free service of <a href="http://brodkinca.com/">Brodkin CyberArts</a>.
                </p>
                <p class="pull-right">
                    Copyright &copy; <?php echo date('Y') ?> All rights reserved.
                </p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>

        <script src="/assets/dist/js/bootstrap.js"></script>
        <script src="/assets/dist/js/plugins.js"></script>
        <script src="/assets/dist/js/app.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-36053085-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>

        <script id="my_repos_tpl" type="text/html">
            <ul class="nav nav-tabs">
                {{#users}}
                <li>
                    <a href="#repos_{{login}}" data-toggle="tab">
                        <img src="{{avatar}}" alt="{{name}} avatar">
                        {{login}}
                    </a>
                </li>
                {{/users}}
            </ul>

            <div class="tab-content">
                {{#users}}
                <div class="tab-pane" id="repos_{{login}}">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Repository</th>
                                <th>Source Branch</th>
                                <th colspan="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#repos}}
                            <tr {{^branch}}class="muted"{{/branch}} data-repo="{{name}}">
                                <td>{{name}}</td>
                                <td>
                                    {{#branch}}
                                    <div class="btn-group">
                                        <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" data-action="branch-select">
                                            <i class="icon-sitemap"></i>
                                            <span class="branch-name">{{branch}}</span> &nbsp;
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                            <li>
                                                <a data-noclick="1">Loading...</a>
                                            </li>
                                        </ul>
                                    </div>
                                    {{/branch}}
                                </td>
                               <td>
                                    <div class="btn-group">
                                        {{#branch}}
                                        <a class="btn btn-mini" data-action="force-update">
                                            <i class="icon-refresh"></i>
                                            Force Update
                                        </a>
                                        <a class="btn btn-mini" href="{{pages_url}}">
                                            <i class="icon-globe"></i>
                                            View Pages
                                        </a>
                                        {{/branch}}
                                        {{^branch}}
                                        <a class="btn btn-mini" data-action="activate">
                                            <i class="icon-bolt"></i>
                                            Activate
                                        </a>
                                        {{/branch}}
                                        <a class="btn btn-mini" href="{{repo_url}}">
                                            <i class="icon-link"></i>
                                            View Repository
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    {{#branch}}
                                    <a data-action="deactivate">
                                        <i class="icon-remove"></i>
                                    </a>
                                    {{/branch}}
                                </td>
                            </tr>
                            {{/repos}}
                        </tbody>
                    </table>
                </div>
                {{/users}}
            </div>
        </script>

        <script id="my_repos_branch_tpl" type="text/html">
            {{#branches}}
            <li><a data-action="branch-update">{{.}}</a></li>
            {{/branches}}
        </script>
    </body>
</html>
