# MIWeb.Neos.Blog
Blog plugin for Neos CMS


## Setup
Add the following to your projects Routes.yaml

    -
      name: 'MIWebNeosBlog'
      uriPattern: '<MIWebNeosBlogSubroutes>'
      subRoutes:
        MIWebNeosBlogSubroutes:
          package: MIWeb.Neos.Blog

