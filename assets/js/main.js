var EventManagerAdmin = (function(){
    var init = function(){
        EventManagerAdmin.List.registerHandlers();
    };

    return { init: init }
})();

EventManagerAdmin.List = (function(){
    var registerHandlers = function(){
        if( jQuery("#removeUser").length )
        {
            jQuery("#removeUser").click(function(){

                var confirmed = confirm("Du kommer nu plocka bort denna användaren från deltagarlistan.");
                if( confirmed )
                {
                    var userId = jQuery(this).parent().parent().data("user");
                    var eventId = jQuery(this).parent().parent().data("event");
                    
                    removeUserFromEventList(userId, eventId);
                }
            });
        }
        if( jQuery("#addUser").length )
        {
            jQuery("#addUser").click(function(){
                var confirmed = confirm("Du kommer nu flytta denna användare från väntelistan till deltagarlistan.");
                if( confirmed )
                {
                    var userId = jQuery(this).parent().parent().data("user");
                    var eventId = jQuery(this).parent().parent().data("event");
                    
                    addUserToEventList(userId, eventId);
                }
            });
        }
    },

    removeUserFromEventList = function(userId, eventId){
        jQuery.ajax({
            type: "POST",
            url: eventusershandling.ajaxurl,
            data: {
                action: 'remove_user',
                userId: userId,
                eventId: eventId
            }
        })
            .done(function(){
                alert("succe");
                location.href = location.href;
            })
            .fail(function(){
                alert("fan");
            });
    },

    addUserToEventList = function(userId, eventId) {
        jQuery.ajax({
            type: "POST",
            url: eventusershandling.ajaxurl,
            data: {
                action: 'add_user',
                userId: userId,
                eventId: eventId
            }
        })
            .done(function(){
                alert("succe");
                location.href = location.href;
            })
            .fail(function(){
                alert("fan");
            });
    };

    return { registerHandlers: registerHandlers }
})();

jQuery(document).ready(function(){
    EventManagerAdmin.init();
});