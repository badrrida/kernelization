$( "#joined" ).click(function() {
  //$("#stats").animate({opacity: "1"}, 1500);
  $("#ufo").addClass("floating");
  $("#ufo").animate({marginLeft: "93px"}, 500);
  $("*").removeClass("whitetext");
  $("#joined").addClass("whitetext");
});

$( "#badges" ).click(function() {
  //$("#stats").animate({opacity: "1"}, 1500);
  $("#ufo").addClass("floating");
  $("#ufo").animate({marginLeft: "293px"}, 500);
  $("*").removeClass("whitetext");
  $("#badges").addClass("whitetext");
});

$( "#projects" ).click(function() {
  //$("#stats").animate({opacity: "0"}, 500);
  $("#ufo").animate({marginLeft: "493px"}, 500);
  $("#ufo").removeClass("floating");
  $("*").removeClass("whitetext");
  $("#projects").addClass("whitetext");
});

$( "#points" ).click(function() {
  //$("#stats").animate({opacity: "1"}, 1500);
  $("#ufo").addClass("floating");
  $("#ufo").animate({marginLeft: "693px"}, 500);
  $("*").removeClass("whitetext");
  $("#points").addClass("whitetext");
});

$( "#friends" ).click(function() {
  //$("#stats").animate({opacity: "1"}, 1500);
  $("#ufo").addClass("floating");
  $("#ufo").animate({marginLeft: "893px"}, 500);
  $("*").removeClass("whitetext");
  $("#friends").addClass("whitetext");
});


 