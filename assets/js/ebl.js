/**
 * Core EBL class for getting endpoint data.
 * Most items in this object use the following pattern:
 * item = new Item(args);
 * item.get(function(res){ // do things here })/
 * @type {{endpoints: *, getEndpoint: ebl.getEndpoint, TemplateLoader: ebl.TemplateLoader, Glass: ebl.Glass, BeerList: ebl.BeerList, Beer: ebl.Beer, TapList: ebl.TapList}}
 */
var ebl = {
  endpoints: eblArgs,

  /**
   * Gets the specified Easy Beer Lister REST API Endpoint.
   * While this can be used by anyone, it's recommended that you use the pre-made objects for each endpoint, instead.
   * @param endpoint
   * @param callback
   * @param data
   */
  getEndpoint: function(endpoint,callback,data){
    jQuery.ajax({
      url:     ebl.endpoints[endpoint],
      type:    "POST",
      data:    data,
      success: callback,
      error:   function(res){
        console.log(res);
      }
    });
  },

  /**
   * Loads HTML template content used in Easy Beer Lister pages
   * @param location - The location (eg:directory) of the template to load
   * @param type - The type (eg:file name) of the template to load
   * @param queryParams - an object of query parameters. Supports all WP_Query params, as well as post_id for single component items
   * @constructor
   */
  TemplateLoader: function(location,type,queryParams){
    this.data = {
      location: location,
      type:     type,
      query:    queryParams
    };

    /**
     * Loads the specified beer template
     */
    this.get = function(callback){
      ebl.getEndpoint('getTemplate',callback,this.data);
    }
  },

  /**
   * Loads a beer glass SVG from the specified ID
   * @param ID - The post ID to load
   * @constructor
   */
  Glass: function(ID){
    this.ID = ID;
    this.template = new ebl.TemplateLoader('component','beer-glass',{post_id: ID});
    this.get = function(callback){
      this.template.loadTemplate(callback);
    }
  },

  /**
   * Loads a list of all of the beers
   * @param args (optional) - WP_Query parameters, passed as an object. Passing args overwrites the default configuration for this query
   * @constructor
   */
  BeerList: function(args){
    this.data = args;

    this.get = function(callback){
      ebl.getEndpoint('beerList',callback,this.data);
    }
  },

  /**
   * Loads a single beer object
   * @param id - The post ID
   * @constructor
   */
  Beer: function(id){
    this.data = {
      id: id
    };

    this.get = function(callback){
      ebl.getEndpoint('beer',callback,this.data);
    }
  },

  /**
   * Loads the current list of beers that are on tap
   * @param args (optional) - WP_Query parameters, passed as an object. Passing args overwrites the default configuration for this query
   * @constructor
   */
  TapList: function(args){
    this.data = args;

    this.get = function(callback){
      ebl.getEndpoint('tapList',callback,this.data);
    }
  },

  /**
   * Loads the current list of beers that are in-season, including all year-round beers
   * @param args (optional) - WP_Query parameters, passed as an object. Passing args overwrites the default configuration for this query
   * @constructor
   */
  InSeasonList: function(args){
    this.data = args;

    this.get = function(callback){
      ebl.getEndpoint('inSeason',callback,this.data);
    }
  },

  /**
   * Loads the current list of beers that are out-of-season
   * @param args (optional) - WP_Query parameters, passed as an object. Passing args overwrites the default configuration for this query
   * @constructor
   */
  OutOfSeasonList: function(args){
    this.data = args;

    this.get = function(callback){
      ebl.getEndpoint('outOfSeason',callback,this.data);
    }
  },

  /**
   * Loads the current list of beers that are available year-round
   * @param args (optional) - WP_Query parameters, passed as an object. Passing args overwrites the default configuration for this query
   * @constructor
   */
  YearRoundList: function(args){
    this.data = args;

    this.get = function(callback){
      ebl.getEndpoint('yearRound',callback,this.data);
    }
  }

};