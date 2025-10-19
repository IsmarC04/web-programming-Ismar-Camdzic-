$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize


  // define routes
 
  app.route({view: 'barberTeamAdmin', load: 'barberTeamAdmin.html' });
  app.route({view: 'accountSettings', load: 'accountSettings.html'});
  app.route({view: 'appointmentsAdmin', load: 'appointmentsAdmin.html'});
  app.route({view: 'barberTeam', load: 'barberTeam.html'});
  app.route({view: 'bookingpage', load: 'bookingpage.html'});
  app.route({view: 'login', load: 'login.html'});
  app.route({view: 'register', load: 'register.html'});
  app.route({view: 'services', load: 'services.html'});
  app.route({view: 'servicesAdmin', load: 'servicesAdmin.html'});
  app.route({view: 'usersAdmin', load: 'usersAdmin.html'});
  app.route({view: 'home', load: 'home.html'})
 
  app.run();

});