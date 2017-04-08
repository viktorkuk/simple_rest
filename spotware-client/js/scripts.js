(function () {    
    var totalPages = 30;
    var currentPage = 0;
    var isLogin = true;
    var apiUrl = "http://spotware-talent.local/api/v1/";
    
    var client = new $.RestClient(apiUrl,{
        ajax : {
            dataType: "json",
            xhrFields: {
                withCredentials: true
            }
        }    
    });
    client.add('books');
    client.add('book');
    client.add('bookrating');
    client.add('authenticate');

    var checkIsLogin = function(){
        
        client.authenticate.read().done(function (data){
            isLogin = data;
            rebildAdminButtons();
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
        client.authenticate.del().done(function (data){
            if(data){ 
                isLogin = false;
                rebildAdminButtons();
            }
        });
    }
    
    var authenticate =  function(){
        
        var hash = CryptoJS.SHA3('' + $('#lg-userneme').val() + $('#lg-password').val());
        $('#login-error').hide();
        
        client.authenticate.create({'hashsum': hash.toString()}).done(function (data){
            if(data.status == 'ok'){
                isLogin = 1;
                $('#lg-userneme,#lg-password').val('');
                $('#loginModal').modal('hide');
                rebildAdminButtons();
            }else{
                $('#login-error h4').text(data.error);
                $('#login-error').show();
            }
        });
    }
      
    var loadBooksPage = function(page){
        client.books.read(page).done(function (data){
            
            var books = data.books;
            totalPages = data.pages;
            currentPage = page;
            
            var book_list_item_template = $('.book_list_item_template');
            $('.book_list_items').html('');

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
            
        });
    }
    
    var getBookRatings = function(isbn){
        client.bookrating.read(isbn).done(function (data){
            var rating = $('#'+isbn).find('.ratings-cont');
            if(data.length){
                 $(data).each(function(){
                    html = '';
                    $.each( this, function( key, value ) {
                      html +=  key + ": " + value + '; ';
                    });                      
                    $(rating).find('ul').append('<li class="list-group-item">'+html+'</li>');
                    $(rating).show();
                 });
            }
        });
    }
        
    var editBook =function(isbn){
        client.book.read(isbn).done(function (data){
            var book = data;
            $('#entry-id').val(book['ISBN']);
            $('#entry-title').val(book['Book-Title']);
            $('#entry-autor').val(book['Book-Author']);                    
            $('#entry-year').val(book['Year-Of-Publication']);
            $('#entry-publisher').val(book['Publisher']);
            $('#bookModal').modal('show');
        });
    }
    
    var deleteBook = function(isbn){
        client.book.del(isbn).done(function (data){
            loadBooksPage(currentPage);
        });
    }
    
    var saveBook = function(){
        var form = $('#entry-form');
        client.book.update($('#entry-id').val(),{'book': form.serialize()}).done(function (data){
            $('#bookModal').modal('hide');
            $(form).find('input').each(function(){
                $(this).val('');
            })
            loadBooksPage(currentPage); 
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
                currentPage = num-1;
                loadBooksPage(num-1);
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000);
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