jQuery(document).ready(function($){

  var eblWrapper = $('#js--filter-target');
  var eblResetContent = null;

  /**
   * Helper function for the beers filter bar
   * @param args
   */
  function eblBeerFilter(args){
    //Check to see if the original content has been stored in a var.
    if(eblResetContent === null){
      //If not, go ahead and save it. We will use this to quickly recall the default query if they click "reset"
      eblResetContent = $(eblWrapper).html();
    }
    //Instantiate the template loader, and pass the specified args.
    var eblTemplate = new ebl.TemplateLoader('wrapper','archive',args);

    //Get the template file markup, and put it in the wrapper.
    $(eblWrapper).addClass('js--is-loading');
    eblTemplate.get(function(res){
      $(eblWrapper).html(res.template);
      $(eblWrapper).removeClass("js--is-loading");

    });
  }

  /**
   * When the on-tap button is clicked, load the template containing all of the beers that are on-tap
   */
  $('.js--ebl-filter-on-tap').on('click',function(){
    eblBeerFilter({type: 'tapList'});
  });

  /**
   * When the in-season button is clicked, load the template containing all of the beers that are in-season
   */
  $('.js--ebl-filter-in-season').on('click',function(){
    eblBeerFilter({type: 'inSeason'});
  });

  /**
   * When the out-of-season button is clicked, load the template containing all of the beers that are out-of-season
   */
  $('.js--ebl-filter-out-of-season').on('click',function(){
    eblBeerFilter({type: 'outOfSeason'});
  });

  /**
   * When the year-round button is clicked, load the template containing all of the beers that are year-round
   */
  $('.js--ebl-filter-year-round').on('click',function(){
    eblBeerFilter({type: 'yearRound'});
  });

  /**
   * When the reset button is clicked, reset the query back to its original state
   */
  $('.js--ebl-filter-reset').on('click',function(){
    if(eblResetContent !== null){
      (eblWrapper).html(eblResetContent);
    }
  });

});