/*global module:false*/
module.exports = function(grunt) {

  // Register Plugins
  grunt.loadNpmTasks('grunt-contrib');

  // Project configuration.
  grunt.initConfig({
    meta: {
      banner:
        '/*! Hubcap - http://hubcap.it/\n' +
        '* Copyright 2011-<%= grunt.template.today("yyyy") %> Brodkin CyberArts.\n' +
        '* http://brodkinca.com/ \n' +
        '* All rights reserved. */'
    },
    less: {
      'bootstrap': {
        files: {
          'assets/dist/css/bootstrap.css': 'assets/src/css/bootstrap.less'
        },
        options: {
          paths: ['assets/lib/bootstrap/less', 'assets/lib/font-awesome/less']
        }
      }
    },
    lint: {
      files: ['grunt.js', 'assets/src/js/app.js', '<config:concat.helpers.src>']
    },
    concat: {
      'bootstrap-js': {
        src: [
          '<banner:meta.banner>',
          'assets/lib/bootstrap/js/bootstrap-alert.js',
          'assets/lib/bootstrap/js/bootstrap-dropdown.js',
          'assets/lib/bootstrap/js/bootstrap-collapse.js',
          'assets/lib/bootstrap/js/bootstrap-modal.js',
          'assets/lib/bootstrap/js/bootstrap-tab.js',
          'assets/lib/bootstrap/js/bootstrap-tooltip.js',
          'assets/lib/bootstrap/js/bootstrap-popover.js',
          'assets/lib/bootstrap/js/bootstrap-transition.js',
          'assets/lib/bootstrap/js/bootstrap-scrollspy.js'
        ],
        dest: 'assets/dist/js/bootstrap.js'
      },
      'helpers': {
        src: [
          'assets/lib/bca-assets-global/js/helper.js',
          'assets/lib/bca-assets-global/js/helper.validation.js'
        ],
        dest: 'assets/dist/js/helpers.js'
      }
    },
    mincss: {
      compress: {
        files: {
          'assets/dist/css/bootstrap.css': 'assets/dist/css/bootstrap.css',
          'assets/dist/css/style.css': 'assets/src/css/style.css'
        }
      }
    },
    min: {
      'bootstrap-js': {
        src: '<config:concat.bootstrap-js.dest>',
        dest: 'assets/dist/js/bootstrap.js'
      },
      'plugins': {
        src: [
          'assets/lib/bootbox/bootbox.js',
          'assets/lib/icanhaz/ICanHaz.js',
          'assets/lib/highlightjs/highlight.pack.js',
          '<config:concat.helpers.dest>'
        ],
        dest: 'assets/dist/js/plugins.js'
      },
      'hubcap': {
        src: ['<banner:meta.banner>', 'assets/src/js/analytics.js', 'assets/src/js/app.js'],
        dest: 'assets/dist/js/app.js'
      }
    },
    watch: {
      files: '<config:lint.files>',
      tasks: 'lint'
    },
    jshint: {
      options: {
        curly: true,
        eqeqeq: true,
        immed: true,
        latedef: true,
        newcap: true,
        noarg: true,
        sub: true,
        undef: true,
        boss: true,
        eqnull: true,
        browser: true
      },
      globals: {
        BCA: false,
        bootbox: false,
        hljs: false,
        ich: false,
        jQuery: false
      }
    }
  });

  // Default task.
  grunt.registerTask('default', 'less concat lint mincss min');

};
