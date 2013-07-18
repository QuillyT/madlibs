function aUser(){
  this.userID = 0;
  this.name = "";
  this.email = "";
}

function aGame(){
  this.gameID = 0;
  this.story = "";
  this.blanks = 0;
  this.playerA = "";
  this.playerB = "";
  this.turn = 0;
  this.inputs = "";
}

function aWord(){
  this.word = "";
}

var user = new aUser();  
var currentGame = new aGame();
var tID, rID;  

$(document).ready(function () {   
  //init page
  $('#navbar').scrollspy();  
  
  var allowNewUserSubmit = {
    "UserName" : false,
    "Password" : false,
    "Email" : false
  };

  $(".btnCloseModal").click(function(event){         //click close modal button
    event.preventDefault();    
    $('#message_modal').modal('hide');          //close message_modal        
    $('#select_modal').modal('hide'); 
    $('#game_modal').modal('hide');   
  });

  
  $("#game_modal").on("hidden",function(){    
    clearTimeout(tID);    
    $('#randomPlayer').prop("disabled",false);    
  });
  
  $("#select_modal").on("hidden",function(){
    $('#selectPlayer').prop("disabled",false);
  })
  
  //GAME PLAY EVENTS////////////////////////////////////////////////////////////
  $('#btnGameSubmit').click(function(){
    event.preventDefault();
    clearTimeout(tID);
    $(this).prop("disabled",true);
    var newWord = $("#guide").val();
    if(newWord===""){
      return;
    }
    
    var cGame = currentGame;        
    var t = parseInt(cGame.turn);
    var c;
    
    if(++t>=(parseInt(cGame.blanks)+1)) c = 1;
    else c = 0;
    
    $.ajax({
      url: "php/submitGame.php",
      type: "get",  
      data: {"gameID":cGame.gameID,"turn":t,"inputs":cGame.inputs+newWord+"|","isComplete":c},
      datatype: "text"
    }).done(function(response, textStatus, jqXHR){
      console.log("AJAX submitGame: "+response);
      if(response.indexOf("ERR")!=-1){        
        MESSAGE("Server Error",response);        
      } 
      //if(response.indexOf("GAME SUBMITTED")!=-1){}      
      $('#guide').val("");      
      
      updateGameModal(cGame.gameID);      
    });
  });   
  
  //NEW GAME EVENTS/////////////////////////////////////////////////////////////
  $("#selectPlayer").click(function(event){
    event.preventDefault();
    $("#select_list").html("");
    
    $(this).prop("disabled",true);
    $("#select_modal").modal('show');
    
    //ajax call all possible players
    $.ajax({
      url: "php/getPlayers.php",
      type: "get",      
      datatype: "text"
    }).done(function(response, textStatus, jqXHR){
      console.log("AJAX getPlayers"+response);
      if(response.indexOf("ERR")!=-1){        
        MESSAGE("Server Error",response);        
      }
      if(response.indexOf("No players. Invite your friends!")!=-1){
        MESSAGE("No Players",response);
      }
      //list them out with play me button
      
      var players = JSON.parse(response);
      var len = players.length;
      var index = 0;
      var dindex = 0;
      var playersList;
      while(index<len){
        var pid=players[index].userID;        
        if(user.userID==pid){
          index++;
          continue;
        }
        playersList += "<tr><td>"+(dindex+1)+"</td><td>"+players[index].name+"</td><td><button class='btn selectGame' name='"+pid+"'>Play!</button></td></tr>";
        index++;
        dindex++;
      }
      $("#select_list").append(playersList);
      $(".selectGame").on("click",function(){
        var id = $(this).attr("name");
        $('#select_modal').modal('hide');
        getPartner(id);
      });
    });
  });
  
  $("#randomPlayer").click(function(event){ 
    event.preventDefault();    
    //start debouncing...
    $(this).prop("disabled",true);
    
    //randomly select player
    $.ajax({
      url: "php/randomPlayer.php",
      type: "get",  
      data: {"userID":user.userID},
      datatype: "text"
    }).done(function(response, textStatus, jqXHR){
      console.log("AJAX randomPlayer: "+response);
      if(response.indexOf("ERR")!=-1){
        MESSAGE("Server Error",response);        
        return;
      }
      if(response==="NO OTHER PLAYERS"){
        MESSAGE("Error","There are no other players with accounts yet.");                
        return;
      }
      var result = JSON.parse(response);
      var playerID = result.userID;
      getPartner(playerID);
    });
  });   
  
  //LOGIN/OUT Events////////////////////////////////////////////////////////////
  $("#btnSignIn").click(function (event) {      //click sign in button
    event.preventDefault();    
    //start debouncing...
    $(this).prop("disabled",true);
    
    var email = $("#email").val();
    var password = $("#password").val();        

    //AJAX search email, grab password
    $.ajax({
      url: "php/signIn.php",
      type: "get",
      data: {"email":email,"password":password},
      datatype: "text"
    }).done(function(response, textStatus, jqXHR){
      console.log("AJAX signIn: "+response);
      if(response.indexOf("ERR")!=-1){
        MESSAGE("Server Error",response);
        return;
        //end debounce
        $("#btnSignIn").prop("disabled",false);
      }
      if(response==="Password doesn't match"){//exit if response is ''
        MESSAGE("Login Error",response);
        //end debounce
        $("#btnSignIn").prop("disabled",false);
        return;
      }      
      if(response==="Account with this email doesn't exist."){//exit if response is ''
        MESSAGE("Login Error",response);
        //end debounce
        $("#btnSignIn").prop("disabled",false);
        return;
      } 
      var result = JSON.parse(response);            
      signIn(result);      
      //load user's games
      
      //end debounce
      $("#btnSignIn").prop("disabled",false);
    });
  });  
  
  $("#btnSignOut").click(function (event) {     //click sign out button
    event.preventDefault();
    clearTimeout(rID);
    signOut(user);
  });
  
  
  //REGISTRATION EVENTS/////////////////////////////////////////////////////////
  $("#btnNewUser").click(function () {          //click new user button
    event.preventDefault();
    $('#new_user').modal();                     //open new user modal
  });

  //click submit new user button
  $('#btnSubmitNewUser').click(function(event){
    event.preventDefault();
    if(allowNewUserSubmit.UserName
      &&allowNewUserSubmit.Password
      &&allowNewUserSubmit.Email){
      
      var newUser = new aUser();
      newUser.email = $('#inputEmail').val();
      newUser.name = $('#inputUserName').val();
      var c = $('#inputPassword').val();
      
      $.ajax({
        url: "php/submitNewUser.php",
        type: "get",
        data: { "email":newUser.email, "name":newUser.name, "password":c },
        datatype: "text",
        cache: false
      }).done(function(response, textStatus, jqXHR){
        console.log("AJAX newUser: "+response);        
        if(response.indexOf("EMAIL EXISTS")!=-1){
          $('#newUserErrorMessage').text("An account with this email already exists.");
          return;
        }
        if(response.indexOf("NAME EXISTS")!=-1){
          $('#newUserErrorMessage').text("An account with this name already exists.");
          return;
        }
        if(response.indexOf("ERR")!=-1){
          $('#newUserErrorMessage').text(response);
          return;
        }        
        newUser.userID = response;
        signIn(newUser);        
        clearUserForm(); //clear form                
      });              
    }
    else{      
      $('#newUserErrorMessage').text("Invalid entry. Please check your inputs."); 
    }
  });

  //change user name
  $('#inputUserName').keyup(function(event){
    event.preventDefault();
    var n = $(this).val();
    //regex: if char or num = OK
    if(n.search(/^[a-zA-Z0-9]+$/)===-1){
      $("#newUserName").removeClass('error success').addClass('error');
      allowNewUserSubmit.UserName = false;
    }    
    else{
      $("#newUserName").removeClass('error success').addClass('success');
      allowNewUserSubmit.UserName = true;
    }
  });
  
  //change user password
  $('#inputPassword').keyup(function(event){
    event.preventDefault();
    var p = $(this).val();  
    var o = $('#inputPassword_confirm').val();    
    if((p.length<5)||(p.search(/\s/)!=-1)){      
      $("#newPassword").removeClass('error success').addClass('error');      
      allowNewUserSubmit.Password = false;
    }
    else{
      $("#newPassword").removeClass('error success').addClass('success');      
      allowNewUserSubmit.Password = true;
    }
    if(p===o){
      $("#newPasswordConfirm").removeClass('error success').addClass('success');      
      allowNewUserSubmit.Password = true;
    }
    else{
      $("#newPasswordConfirm").removeClass('error success').addClass('error');      
      allowNewUserSubmit.Password = false;
    } 
  });
  
  //change user password confirm
  $('#inputPassword_confirm').keyup(function(event){
    event.preventDefault();
    var p = $(this).val();  
    var o = $('#inputPassword').val();
    if(p===o){
      $("#newPasswordConfirm").removeClass('error success').addClass('success');      
      allowNewUserSubmit.Password = true;
    }
    else{
      $("#newPasswordConfirm").removeClass('error success').addClass('error');      
      allowNewUserSubmit.Password = false;
    }   
  });  
  
  //change user email
  $('#inputEmail').keyup(function(event){
    event.preventDefault();
    var e = $(this).val();  
    var o = $('#inputEmail_confirm').val();
    //regex: if char or num = OK
    if(e.search(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)===-1){
      $("#newEmail").removeClass('error success').addClass('error');
      allowNewUserSubmit.Email = false;
    }    
    else{
      $("#newEmail").removeClass('error success').addClass('success');
      allowNewUserSubmit.Email = true;
    }
    if(e===o){
      $("#newEmailConfirm").removeClass('error success').addClass('success');      
      allowNewUserSubmit.Email = true;
    }
    else{
      $("#newEmailConfirm").removeClass('error success').addClass('error');      
      allowNewUserSubmit.Email = false;
    } 
  });
  
  //change user email confirm
  $('#inputEmail_confirm').keyup(function(event){
    event.preventDefault();
    var e = $(this).val();  
    var o = $('#inputEmail').val();
    //regex: if char or num = OK
    if(e===o){
      $("#newEmailConfirm").removeClass('error success').addClass('success');      
      allowNewUserSubmit.Email = true;
    }
    else{
      $("#newEmailConfirm").removeClass('error success').addClass('error');      
      allowNewUserSubmit.Email = false;
    } 
  });  
});//.ready()

////////////////////////////////////////////////////////////////////////////////
function getPartner(playerID){
  $.ajax({
    url: "php/createPair.php",
    type: "get",  
    data: {"userID":user.userID,"playerID":playerID},
    datatype: "text"
  }).done(function(response, textStatus, jqXHR){    
    console.log("AJAX createPair: "+response);
    if(response.indexOf("ERR")!=-1){
      MESSAGE("Server Error",response);      
      return;
    }
    var result = JSON.parse(response);    
    var game_obj = new aGame();
    game_obj.pairID = result.pairID;    
    createGame(game_obj);       
  });
}

function createGame(game_obj){
  //random select story
  //create game with story, pair, turn[0], blank inputs
  $.ajax({
    url: "php/createGame.php",
    type: "get",  
    data: {"name":user.name,"pairID":game_obj.pairID},
    datatype: "text"
  }).done(function(response, textStatus, jqXHR){
    console.log("AJAX createGame: "+response);
    if(response.indexOf("ERR")!=-1){
      MESSAGE("Server Error",response);     
      return;
    }
    var results = JSON.parse(response);    
    game_obj.gameID = results.gameID;
    game_obj.storyID = results.storyID;
    game_obj.turn = results.turn;
    game_obj.inputs = "";
    
    openGameModal(game_obj.gameID);
  });
}  

function openGameModal(gameID){  
  updateGameModal(gameID);  
  $('#game_modal').modal('show');       
}

function updateGameModal(gameID){  
  
  //AJAX: get story obj
  $.ajax({
    url: "php/getGame.php",
    type: "get",  
    //data: {"storyID":game_obj.storyID},
    data: {"gameID":gameID},
    datatype: "text"
  }).done(function(response, textStatus, jqXHR){
    console.log("AJAX getGame: "+response);
    if(response.indexOf("ERR")!=-1){
      MESSAGE("Server Error",response);
      return;
    }        
    currentGame = JSON.parse(response);      
    var game_obj = currentGame;
    var luser = user;
    var lturn = parseInt(game_obj.turn);
    var guide = new aWord();
    var sentence;
    var p1 = game_obj.player1;
    var p2;
    var pA = game_obj.playerA;
    var pB = game_obj.playerB;
    
    //clear inputs
    $('#game_input').hide();  
    $("#guide").html("");
    $("#progress").html("");
    
    if(p1==pA){
      $('#player1').html(pA);
      $('#player2').html(pB);
      p2 = pB;
    }
    else{      
      $('#player1').html(pB);
      $('#player2').html(pA);
      p1 = pB;
      p2 = pA;
    }
    
    if(lturn%2==1){
      $('#game_header').text(p1+"'s turn!");
    }
    else{
      $('#game_header').text(p2+"'s turn!");
    }
    
    //display game progress   
    sentence = makeSentenceHTML(game_obj, guide);    
    $("#progress").append(sentence);

    if(lturn==game_obj.blanks+1){
      $("#game_header").text("Game Finished!");
    }
    else{
      if(((luser.name==p1)&&(lturn%2==1))||
         ((luser.name==p2)&&(lturn%2==0))){
        clearTimeout(tID);
        $('#game_header').text("Your turn!");
        $('#guide').attr("placeholder",guide.word);
        $('#game_input').show();        
      }
      else{
        tID = setTimeout(function(){
          updateGameModal(gameID);
        },3000);
      }
    }
    
    $('#btnGameSubmit').prop("disabled",false);
  });  
}

function makeSentenceHTML(game_obj, guide){
  var story = game_obj.story;  
  var inputs = game_obj.inputs.split("|");  
  var lturn = parseInt(game_obj.turn);
  var input = "";  
  var pointer=0;
  var inputHTML;  
  var lb=0, rb=0;
  var sentence="";
  while(pointer<lturn){
    lb=story.indexOf("[",rb);
    if(lb===-1){        
      //END CASE, grabs the rest of the story.
      //$("#progress").append(story.substring(rb+1));    
      sentence += story.substring(rb+1);
      break;
    }    

    if(rb===0){
      //$("#progress").append(story.substring(rb,lb));      
      sentence += story.substring(rb,lb);
    }
    else{
      //$("#progress").append(story.substring(rb+1,lb));  
      sentence += story.substring(rb+1,lb);
    }

    input = inputs[pointer];
    if(lb===0){
      input = input.charAt(0).toUpperCase() + input.slice(1);
    }    
    if(pointer%2==0){
      inputHTML = "<span class='text-red'>"+input+"</span>";    
    }
    else{
      inputHTML = "<span class='text-green'>"+input+"</span>";    
    }
    //$("#progress").append(inputHTML);
    sentence += inputHTML;

    //find next guide
    rb = story.indexOf("]",lb);
    guide.word = story.substring(lb+1,rb);            
    pointer++;   
  }
  return sentence;
}

function signIn(user_data) {  
  user.userID = user_data.userID;
  user.email = user_data.email;
  user.name = user_data.name;
  
  $('.signed_in').toggle(); //toggle DOM elements
  $('#email').val("");      //clear login info
  $('#password').val("");
  $('#userName').text("Hello, "+user.name+"!");    
   
  refreshGames();
}

function signOut() {  
  user.userID = "";
  user.email = "";
  user.name = "";
  
  $('.signed_in').toggle();
  $('#userName').text("Madlibs!");  
  $('#currentGames').html("");
  $('#oldGames').html("");  
}

function clearUserForm(){
  $('#inputUserName,#inputPassword,#inputPassword_confirm,#inputEmail,#inputEmail_confirm').val('');  
  $("#newUserName").removeClass('error success');
  $("#newEmail").removeClass('error success');
  $("#newEmailConfirm").removeClass('error success');      
  $("#newPassword").removeClass('error success');
  $("#newPasswordConfirm").removeClass('error success');
  $('#new_user').modal('hide');   
}

function MESSAGE(header,msg){
  $('#modal_header').text(header);
  $('#modal_msg').text(msg);
  $('#message_modal').modal('show');  
}

function refreshGames(){  
  $.ajax({
    url: "php/getGames.php",
    type: "get",
    data: {"userID":user.userID},
    datatype: "text"
  }).done(function(response, textStatus, jqXHR){
    console.log("AJAX getGames as "+user.name+": "+response);
    if(response.indexOf("ERR")!=-1){
      MESSAGE("Server Error",response);
      return;
    }    
    if(response.indexOf("No games. Start one!")!=-1){
      MESSAGE("Game Issue",response);
      return;
    }
    var games = JSON.parse(response);    
    var index = 0;
    var len = games.length;
    var currentTable = $("#currentGames");
    var completeTable = $("#oldGames");
    var appendToCurrent = "";
    var appendToComplete = "";    
    var currentIndex = 0;
    var completeIndex = 0;
    var yourTurn;
    var p1, p2;    
    
    while(index<len){
      if(games[index].playerA===games[index].player1){
        p1 = games[index].playerA;
        p2 = games[index].playerB;
      }
      else{
        p1 = games[index].playerB;
        p2 = games[index].playerA;
      }
      var gameID = games[index].gameID;  
      
      if(games[index].turn%2==1&&(p1==user.name)){        
        yourTurn = "info";
      }
      if(games[index].turn%2==0&&(p2==user.name)){        
        yourTurn = "info";
      }
      
      if(games[index].isComplete==0){
        currentIndex++;
        
        appendToCurrent += "<tr class='"+yourTurn+"'><td class='game' title='"+gameID+"'>"+currentIndex+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+p1+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+p2+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+games[index].turn+"</td></tr>";
      }
      else{
        completeIndex++;
        appendToComplete += "<tr><td class='game' title='"+gameID+"'>"+completeIndex+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+p1+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+p2+"</td>"
                              +"<td class='game' title='"+gameID+"'>"+games[index].turn+"</td></tr>";
      }
      index++;
    }
    currentTable.html("");    
    currentTable.append(appendToCurrent);
    completeTable.html("");
    completeTable.append(appendToComplete);    
    
    $(".game").on("click",function(){
      var id = $(this).attr("title");            
      openGameModal(id);
    });
    
    rID = setTimeout(function(){
      refreshGames();  
    },5000) ;
  });
}

