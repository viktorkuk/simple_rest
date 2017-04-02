<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My test news blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
    <link href="/css/mycss.css" rel="stylesheet">
    <script src="/bootstrap/js/jquery-2.2.4.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/bootstrap/js/jquery.bootpag.min.js"></script>    
    <script src="/bootstrap/js/bootstrap-confirmation.min.js"></script>
    <script src="/bootstrap/js/sha3.js"></script>
</head>

<script>
    (function () {    
    var totalPages = 30;
    var currentPage = 0;
    var isLogin = 0;
    
    var checkIsLogin = function(){
        $.ajax({
            url: "http://spotware-talent.local/authenticate/",
            type: "GET",
            crossDomain: true,
            dataType: "json",
            xhrFields: {
                withCredentials: true
            },
            success: function (response) {
               isLogin = response.authenticate;
               rebildAdminButtons();
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var rebildAdminButtons =  function(){
        if(isLogin){
            $('.item-edit, .item-remove, #logout-btn').show();
            $('#login-btn').hide();
        }else{
            $('.item-edit, .item-remove, #logout-btn').hide();
            $('#login-btn').show();
        }
    }
    
    var logout = function(){
        $.ajax({
            url: "http://spotware-talent.local/authenticate/",
            type: "DELETE",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            dataType: "json",
            async: false,
            success: function (response) {
                if(response.status == 'ok'){ 
                    isLogin = false;
                    rebildAdminButtons();
                }else{
                    alert("error " + response);
                }
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var authenticate =  function(){
        
        var hash = CryptoJS.SHA3('' + $('#lg-userneme').val() + $('#lg-password').val());
        
        //console.log(hash.toString());
        $('#login-error').hide();
        
        $.ajax({
            url: "http://spotware-talent.local/authenticate/",
            type: "POST",
            crossDomain: true,
            data: {
                'hashsum': hash.toString()
            },
            xhrFields: {
                withCredentials: true
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 'ok'){
                    isLogin = 1;
                    $('#lg-userneme,#lg-password').val('');
                    $('#loginModal').modal('hide');
                    rebildAdminButtons();
                }else{
                    $('#login-error h4').text(response.error);
                    $('#login-error').show();
                }
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var loadBooksPage = function(page){

        $.ajax({
            url: "http://spotware-talent.local/books/"+page,
            type: "GET",
            crossDomain: true,
            //data: JSON.stringify(somejson),
            dataType: "json",
            async: false,
            success: function (response) {
                var books = response.books;
                var book_list_item_template = $('.book_list_item_template');
                $('.book_list_items').html('');
                totalPages = response.pages;
                currentPage = page;
                
                $(books).each(function(){
                    var book_list_item = book_list_item_template.clone();
                    $(book_list_item).attr('class','book_list_item');
                    $(book_list_item).attr('data-isbn',this['ISBN']);
                    $(book_list_item).attr('id',this['ISBN']);
                    $(book_list_item).find('.thumbnail img').attr('src',this['Image-URL-M']);
                    $(book_list_item).find('.title').html(this['Book-Title']);
                    $(book_list_item).find('.autor').html(this['Book-Author']);                    
                    $(book_list_item).find('.publisher').html(this['Publisher']);
                    $(book_list_item).find('.publication-date').html(this['Year-Of-Publication']);
                    $(book_list_item).appendTo('.book_list_items');
                    getBookRatings(this['ISBN']);
                });
                
                addEvents();
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var getBookRatings = function(isbn){

        $.ajax({
            url: "http://spotware-talent.local/ratings/"+isbn,
            type: "GET",
            crossDomain: true,
            dataType: "json",
            success: function (response) {
                var rating = $('#'+isbn).find('.ratings-cont');
                if(response.ratings.length){
                     $(response.ratings).each(function(){
                        //country":"canada","votes":"2","rating":"7.5000
                        html = '';
                        $.each( this, function( key, value ) {
                          html +=  key + ": " + value + '; ';
                        });                      
                        $(rating).find('ul').append('<li class="list-group-item">'+html+'</li>');
                        $(rating).show();
                     });
                }
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var initPaginator = function(pagesCount){
            $('.page-selection').bootpag({
                total: pagesCount,
                page: 1,
                maxVisible: 5,
                leaps: true,
                firstLastUse: true,
                first: '←',
                last: '→',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            }).on("page", function(event, num){
                //$(".content4").html("Page " + num); // or some ajax content loading...
                loadBooksPage(num-1);
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
            }); 
    }
    
    var editBook =function(isbn){
        $.ajax({
            url: "http://spotware-talent.local/book/"+isbn,
            type: "GET",
            crossDomain: true,
            dataType: "json",
            async: false,
            success: function (response) {
                var book = response.book;
                $('#entry-id').val(book['ISBN']);
                $('#entry-title').val(book['Book-Title']);
                $('#entry-autor').val(book['Book-Author']);                    
                $('#entry-year').val(book['Year-Of-Publication']);
                $('#entry-publisher').val(book['Publisher']);
                $('#bookModal').modal('show');
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    
    var deleteBook =function(isbn){
        $.ajax({
            url: "http://spotware-talent.local/book/"+isbn,
            type: "DELETE",
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            dataType: "json",
            async: false,
            success: function (response) {
                if(response.status == 'ok'){ 
                    loadBooksPage(currentPage);
                }else{
                    alert("error " + response);
                }
            },
            error: function (xhr, status) {
                alert("error " + xhr.statusText);
            }
        });
    }
    
    var saveBook = function(){
        
        var form = $('#entry-form');
        
        $.ajax({
            url: "http://spotware-talent.local/book/",
            type: "PUT",
            crossDomain: true,
            data: form.serialize(),
            xhrFields: {
                withCredentials: true
            },
            dataType: "json",
            //async: false,
            success: function (response) {
                //var book = response.book;
                
                if(response.status == 'ok'){ 
                    $('#bookModal').modal('hide');
                    $(form).find('input').each(function(){
                        $(this).val('');
                    })
                    loadBooksPage(currentPage);
                }else{
                    alert("error " + response);
                }                    
            },
            error: function (xhr, status) {
                alert("error " + xhr.statusText);
            }
        });
    }
    
    var addEvents = function(){
        $('.item-remove').confirmation({
            title: 'Ara you sure?',
            onConfirm: function() {
                deleteBook($(this).closest('.book_list_item ').data('isbn'));
            },
        });

        $('.item-edit').on( "click", function(ev){
            ev.preventDefault();
            editBook($(this).closest('.book_list_item ').data('isbn'));
        });
    }
    

    $( document ).ready(function() {
        loadBooksPage(0);
        initPaginator(totalPages);
        addEvents();
        checkIsLogin();
        
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        })
        
        $('.save-book').on( "click", function(ev){
            saveBook();
        });
        
        $('#login-btn').on( "click", function(ev){
            $('#lg-userneme,#lg-password').val('');
            $('#login-error').hide();
            $('#loginModal').modal('show');
        });
        
        $('#login-form-btn').on( "click", function(ev){
            authenticate();
        });
        
        $('#logout-btn').on( "click", function(ev){
            logout();
        });
    });
}($));
</script>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
          <a class="navbar-brand" href="#">Book catalog</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#" id="login-btn">Login</a></li>
                <li><a href="#" id="logout-btn" style="display:none">Logout</a></li>
            </ul>
        </div>
    </div>    
</nav>

<div class="modal fade bs-example-modal-lg" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gridSystemModalLabel">Edit book</h4>
        </div>
        <div class="modal-body">
            <form id="entry-form" >
                <input type="hidden" name='ISBN' id="entry-id" value="" />               
                <div class="form-group">
                    <label for="Book-Title" class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="entry-title" name="Book-Title">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="Book-Author" class="col-sm-3 control-label">Book-Author</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="entry-autor" name="Book-Author">
                    </div>
                </div>
                <br>    
                <div class="form-group">
                    <label for="Year-Of-Publication" class="col-sm-3 control-label">Year-Of-Publication</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="entry-year" name="Year-Of-Publication">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="Publisher" class="col-sm-3 control-label">Publisher</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="entry-publisher" name="Publisher">
                    </div>
                </div>
                <br>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary save-book">Save changes</button>
        </div>
    </div>
  </div>
</div>



<div class="modal fade bs-example-modal-lg" id="loginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Login</h4><br>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="userneme" class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="lg-userneme" name="userneme">
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="lg-password" name="password">
                    </div>
                </div>
                <br>
            </div>
            <div id="login-error" style="display: none">
                    <h4 style="color: red; text-align: center"></h4>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  id="login-form-btn">Sign in</button>
            </div>
        </div>
    </div>
</div>

    


<div class="container">
    <div class="book_list_items"></div>
    <div class="page-selection"></div>
    <br><br><br><br>
</div>



<div class="" style="display: none;">
    <div class="book_list_item_template">    
        <div class="media"  data-isbn="">
            <div class="media-left">
                <span class="thumbnail" style="width: 123px; height: 160px">
                    <img class="media-object" src="...">
                </span>
            </div>
            <div class="media-body" style="width: 500px">
                <h4 class="media-heading title">Media heading</h4><br>
                <i class="glyphicon glyphicon-user"></i> by <span class="autor">John</span><br>
                <i class="glyphicon glyphicon-calendar"></i> <span class="publication-date">Sept 16th, 2012</span><br>
                <i class="glyphicon glyphicon-share"></i> <span class="publisher"> publisher</span>
                <br>
                <br>
                <a href="#" class="item-edit" style="display: none;" ><i class="glyphicon glyphicon glyphicon-pencil"></i> Edit item </a><br>
                <a href="#" class="item-remove" style="display: none;" data-toggle="confirmation" data-title="Open Google?" ><i class="glyphicon glyphicon-remove-circle"></i> Remove item </a><br>
                
            </div>
            <div class="media-left ratings-cont" style="display: none;">
                <h4>Ratings:</h4>
                <ul class="list-group">
                </ul>
            </div>
        </div>
        <hr>
    </div>    
</div>

</body>
</html>