<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);

require('templates/header.php'); ?>

<script>
(function () {    
    var totalPages = 30;
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
                
                $(books).each(function(){
                    var book_list_item = book_list_item_template.clone();
                    $(book_list_item).attr('class','book_list_item');
                    $(book_list_item).attr('data-isbn',this['ISBN']);
                    $(book_list_item).find('.thumbnail img').attr('src',this['Image-URL-M']);
                    $(book_list_item).find('.title').html(this['Book-Title']);
                    $(book_list_item).find('.autor').html(this['Book-Author']);                    
                    $(book_list_item).find('.publisher').html(this['Publisher']);
                    $(book_list_item).find('.publication-date').html(this['Year-Of-Publication']);
                    $(book_list_item).appendTo('.book_list_items');
                });
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
            }); 
    }
    

    $( document ).ready(function() {
        loadBooksPage(0);
        initPaginator(totalPages);
        
        
        $('.book_list_item a').on( "click", function(){
            
            var isbn = $(this).closest('.book_list_item ').data('isbn');
            
            console.log( isbn );
            
            $.ajax({
            url: "http://spotware-talent.local/book/"+isbn,
            type: "GET",
            crossDomain: true,
            //data: JSON.stringify(somejson),
            dataType: "json",
            async: false,
            success: function (response) {
                    var book = response.book;
                    console.log(book);
                    $('#entry-id').html(book['ISBN']);
                    $('#entry-title').html(book['Book-Title']);
                    $('#entry-autor').html(book['Book-Author']);                    
                    $('#entry-year').html(book['Year-Of-Publication']);
                    $('#entry-publisher').html(book['Publisher']);
                    $('#bookModal').modal('show');
                },
                error: function (xhr, status) {
                    alert("error " + status);
                }
            });
            
            
            //window.location = '/book.php?isbn='+isbn;
        });
        
        
    });
}($));    
</script>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="gridSystemModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
            <form id="entry-form" action="" method="POST">
                <input type="hidden" name="ISBN" id="entry-id" value="" />
                <label>Title</label>
                <input Book-Title="title" type="text" id="entry-title" /><br>
                <label>Book-Author</label>
                <input name="title" type="text" id="entry-autor" /><br>
                <label>Year Of Publication</label>
                <input name="Year-Of-Publication" type="text" id="entry-year" /><br>
                <label>Publisher</label>
                <input name="Publisher" type="text" id="entry-publisher" /><br>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary save-book">Save changes</button>
        </div>
    </div>
  </div>
</div>



<div class="container">
    

<h1>Books list</h1>
<div class="page-selection"></div>
<div class="book_list_items"></div>
<div class="page-selection"></div>
<br><br><br><br>




<div class="" style="display: none;">

<div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit book</h4>
            </div>
            <!--<div class="modal-body">
                <form id="entry-form" action="" method="POST" class="well">
                    <input type="hidden" name="ISBN" id="entry-id" value="" />
                    <label>Title</label>
                    <input Book-Title="title" type="text" id="entry-title" />
                    <label>Book-Author</label>
                    <input name="title" type="text" id="entry-autor" />
                    <label>Year Of Publication</label>
                    <input name="Year-Of-Publication" type="text" id="entry-year" />
                    <label>Publisher</label>
                    <input name="Publisher" type="text" id="entry-publisher" />
                </form>
            </div>-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary save-book">Save changes</button>
            </div>
        </div>
    </div>
</div>  
    
    
<div class="book_list_item_template">    
    <div class="media"  data-isbn="">
        <div class="media-left">
            <a href="#" class="thumbnail" style="width: 123px; height: 160px">
                <img class="media-object" src="...">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading title">Media heading</h4><br>
            <i class="glyphicon glyphicon-user"></i> by <span class="autor">John</span><br>
            <i class="glyphicon glyphicon-calendar"></i> <span class="publication-date">Sept 16th, 2012</span><br>
            <i class="glyphicon glyphicon-share"></i> <span class="publisher"> publisher</span>
        </div>
    </div>
</div>    
    
    
    
    
    
</div>


</div>

<?php require('templates/footer.php') ?>