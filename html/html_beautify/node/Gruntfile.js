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
      // Prettify a files
      one: {
        src: 'tmp/content.txt',
        dest: 'tmp/content.txt'
      },
    }

  });

  grunt.registerTask('pretty', [
    'prettify'
  ]);


};


