<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);

require('templates/header.php'); ?>

<script>
(function () {    
    var totalPages = 30;
    var currentPage = 0;
    
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
                    $(book_list_item).find('.thumbnail img').attr('src',this['Image-URL-M']);
                    $(book_list_item).find('.title').html(this['Book-Title']);
                    $(book_list_item).find('.autor').html(this['Book-Author']);                    
                    $(book_list_item).find('.publisher').html(this['Publisher']);
                    $(book_list_item).find('.publication-date').html(this['Year-Of-Publication']);
                    $(book_list_item).appendTo('.book_list_items');
                });
                
                $('.book_list_item a').on( "click", function(ev){
                    ev.preventDefault();
                    editBook($(this).closest('.book_list_item ').data('isbn'));
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
    
    var editBook =function(isbn){
        $.ajax({
            url: "http://spotware-talent.local/book/"+isbn,
            type: "GET",
            crossDomain: true,
            //data: JSON.stringify(somejson),
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
    
    var saveBook = function(){
        
        var form = $('#entry-form');
        
        $.ajax({
            url: "http://spotware-talent.local/book/",
            type: "PUT",
            crossDomain: true,
            data: form.serialize(),
            dataType: "json",
            //async: false,
            success: function (response) {
                //var book = response.book;
                console.log(response);
                
                if(response.status == 'ok'){ 
                    $('#bookModal').modal('hide');
                    $(form).find('input').each(function(){
                        $(this).val('');
                    })
                    loadBooksPage(currentPage);
                }else{
                    lert("error " + response);
                }                    
            },
            error: function (xhr, status) {
                alert("error " + status);
            }
        });
    }
    

    $( document ).ready(function() {
        loadBooksPage(0);
        initPaginator(totalPages);
        
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        })

        $('.book_list_item a').on( "click", function(ev){
            ev.preventDefault();
            editBook($(this).closest('.book_list_item ').data('isbn'));
        });
        
        $('.save-book').on( "click", function(ev){
            saveBook();
        });
    });
}($));    
</script>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

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



<div class="container">
    

<h1>Books list</h1>
<div class="page-selection"></div>
<div class="book_list_items"></div>
<div class="page-selection"></div>
<br><br><br><br>




<div class="" style="display: none;">
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