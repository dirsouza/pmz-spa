(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/usuarios","name":"usuarios.index","action":"App\Http\Controllers\UsuarioController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/usuarios\/create","name":"usuarios.create","action":"App\Http\Controllers\UsuarioController@create"},{"host":null,"methods":["POST"],"uri":"api\/v1\/usuarios","name":"usuarios.store","action":"App\Http\Controllers\UsuarioController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/usuarios\/{}","name":"usuarios.show","action":"App\Http\Controllers\UsuarioController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/usuarios\/{}\/edit","name":"usuarios.edit","action":"App\Http\Controllers\UsuarioController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"api\/v1\/usuarios\/{}","name":"usuarios.update","action":"App\Http\Controllers\UsuarioController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/v1\/usuarios\/{}","name":"usuarios.destroy","action":"App\Http\Controllers\UsuarioController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/perfis","name":"perfis.index","action":"App\Http\Controllers\PerfilController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/perfis\/create","name":"perfis.create","action":"App\Http\Controllers\PerfilController@create"},{"host":null,"methods":["POST"],"uri":"api\/v1\/perfis","name":"perfis.store","action":"App\Http\Controllers\PerfilController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/perfis\/{}","name":"perfis.show","action":"App\Http\Controllers\PerfilController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/perfis\/{}\/edit","name":"perfis.edit","action":"App\Http\Controllers\PerfilController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"api\/v1\/perfis\/{}","name":"perfis.update","action":"App\Http\Controllers\PerfilController@update"},{"host":null,"methods":["DELETE"],"uri":"api\/v1\/perfis\/{}","name":"perfis.destroy","action":"App\Http\Controllers\PerfilController@destroy"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // apiLaroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // apiLaroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // apiLaroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // apiLaroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // apiLaroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // apiLaroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.apiLaroute = laroute;
    }

}).call(this);

