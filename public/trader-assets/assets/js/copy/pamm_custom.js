$(document).ready(function () {
  $("#fx-master-grid-btn").click(function () {
    $("#tab").css("display", "none");
    $("#tab2").css("display", "block");
    setTimeout(() => {
      $("#fx-grid-view-content").find(".fx-datatbl-loader").css("display", "none");
    }, 500);
  });
  // list view
  $("#fx-master-list-btn").click(function () {
    $("#tab").css("display", "block");
    $("#tab2").css("display", "none");
    setTimeout(() => {
      $("#fx-grid-view-content").find(".fx-datatbl-loader").css("display", "none");
    }, 500);
  });
});

// showing a search box
$('.rating__search').click(function(e) {
  e.preventDefault();
  $(this).addClass('_is-active');

  $(this).find('input.rating__search-input').css('visibility', 'visible').focus();
})

// hide the search box
$('.rating__search-input').blur(function(e) {
  e.preventDefault();
  $('.rating__search').removeClass('_is-active');
})

//fade in and fade out 
$('.filter-more').click(function(e) {
  e.preventDefault();
  $('.fade-section').slideToggle('slow');
})







 