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
    <script src="/bootstrap/js/jquery.rest.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/bootstrap/js/jquery.bootpag.min.js"></script>    
    <script src="/bootstrap/js/bootstrap-confirmation.min.js"></script>
    <script src="/bootstrap/js/sha3.js"></script>
    <script src="/js/scripts.js"></script>
</head>

<script>

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