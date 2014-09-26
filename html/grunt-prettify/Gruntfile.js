'use strict';

module.exports = function(grunt) {

  require('load-grunt-tasks')(grunt);

  grunt.initConfig({

    prettify: {
      options: {
        "indent": 4,
        "condense": true,
        "indent_inner_html": true,
        "unformatted": [
          "a", "code", "pre"
        ]
      },
      // Prettify a directory of files
      all: {
        expand: true,
        cwd: 'from/',
        dest: 'to/',
        src: ['*.html','*.htm','*.css','*.js','*.txt']
      },
    }

  });

  grunt.registerTask('pretty', [
    'prettify'
  ]);


};


