/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    $("a.confirm_before_action").click(function(){
        var action_confirm = confirm("Are you sure with your action?");
        if(!action_confirm)return false;
    });
});

