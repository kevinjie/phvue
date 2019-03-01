'use strict'
module.exports = {
  module: 'demo', // your module name
  proxyTable: {
    '/demo': { // set your module
      'target': 'http://phvue.demo.com:8080', // set your host
      'changeOrigin': true,
      'pathRewrite': {
        '^/demo': '/demo' // set your module
      }
    }
  },
  controllerAction: [ // controller_action
    'index_index'
  ]
}
