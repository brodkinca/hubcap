jQuery(function() {

    if (jQuery('#my_repos').length > 0) {
        jQuery.getJSON('/ajax/repos').success(function (data) {
            var myReposTemplate = ich.my_repos_tpl(data);
            jQuery('#my_repos #target').html(myReposTemplate);
            jQuery('#my_repos #target .nav-tabs a:first').tab('show');
            jQuery('#my_repos #target .dropdown-toggle').dropdown();
        });
    }

    jQuery('#my_repos #target').on('click', 'a[data-action="activate"]', function () {
        var trigger = jQuery(this);
        var repo = trigger.closest('tr').data('repo');
        bootbox.confirm(
            "Are you sure that you want to activate hubcap for " + repo + "?",
            function(confirm) {
            if (confirm) {
                jQuery.post('/ajax/activate', {"repo": repo}).success(function () {
                    window.location.reload();
                }).error(function () {
                    bootbox.alert('ERROR: Could not activate repository.');
                });
            }
        });
    });

    jQuery('#my_repos #target').on('click', 'a[data-action="force-update"]', function () {
        var trigger = jQuery(this);
        var row = trigger.closest('tr');
        var repo = row.data('repo');
        var branch = row.find('.branch-name').text();

        bootbox.confirm(
            "Forcing an update will cause the latest commit to branch " +
            branch + " of " + repo + " to be queued for deployment. \n" +
            "Are you sure that you want to continue?",
            function(confirm) {
            if (confirm) {
                jQuery.post('/ajax/repo_force_update', {"repo": repo}).error(function () {
                    bootbox.alert('ERROR: Could not send update request.');
                });
            }
        });
    });

    jQuery('#my_repos #target').on('click', 'a[data-action="deactivate"]', function () {
        var trigger = jQuery(this);
        var repo = trigger.closest('tr').data('repo');
        bootbox.confirm(
            "Are you sure that you want to deactivate hubcap for " + repo + "?",
            function(confirm) {
            if (confirm) {
                jQuery.post('/ajax/deactivate', {"repo": repo}).success(function () {
                    window.location.reload();
                }).error(function () {
                    bootbox.alert('ERROR: Could not activate repository.');
                });
            }
        });
    });

    jQuery('#my_repos #target').on('mouseup', 'a[data-action="branch-select"]', function () {
        var trigger = jQuery(this);
        var repo = trigger.closest('tr').data('repo');

        jQuery.getJSON('/ajax/repo_branches', {"repo": repo}).success(function (data) {
            var myReposBranchTemplate = ich.my_repos_branch_tpl(data);
            trigger.next('.dropdown-menu').html(myReposBranchTemplate);
        }).error(function () {
            bootbox.alert('ERROR: Could not load branches from API.');
        });
    });

    jQuery('#my_repos #target').on('click', 'a[data-action="branch-update"]', function () {
        var trigger = jQuery(this);
        var repo = trigger.closest('tr').data('repo');
        var branch = trigger.text();

        if (!trigger.data('data-noclick')) {
            jQuery.post(
                '/ajax/repo_branch_update',
                {"repo": repo, "branch": branch}
            ).success(function (data) {
                trigger.closest('.dropdown-menu').prev('.dropdown-toggle').find('.branch-name').text(branch);
            }).error(function () {
                bootbox.alert('ERROR: Could not update repository.');
            });
        }

    });

    hljs.initHighlighting();

    jQuery('#nav_main').scrollspy();

});
