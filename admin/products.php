<?php
session_start();
$do = "";
if(isset($_GET['do']))
{
    // echo $_GET['do'];
    $do = $_GET['do'];
}
else
{
    // echo "sorry";
    $do = "manage";
}
?>
<?php if(isset($_SESSION['USER_NAME'])):?>
    <?php include_once "language.php"?>
    <?php include "resources/includes/header.php"?>
    <?php require "config.php"?>
    <?php include "resources/includes/navbar.php"?>

    <!-- Start Member CRUD Page-->

    <?php if($do == "manage"):?>
    <!--Start all members page-->
    <?php 
        // Select All From Database
        $stmt=$con->prepare("SELECT * FROM products WHERE product_discount=1");
        $stmt->execute();
        $products =$stmt->fetchAll();
        
    ?>
    <div class="container">
        <h1 class="text-center">All Products</h1>
        <!-- Add Members -->
        <a class="btn btn-primary m-3" href="?do=add">
            <i class="fas fa-user-plus"></i>Add Product
        </a>
        <table class="table">
<thead>
    <tr>
        <th scope="col">Product Name</th>
        <th scope="col">Product Category</th>
        <th scope="col">Created at</th>
        <th scope="col">Control</th>
    </tr>
</thead>
<tbody>
<?php foreach($products as $product):?>
    <tr>
        <!-- php echo IS THE SAME AS = -->
        <th scope="row"><?= $product['product_name']?></th>
        <td><?= $product['product_category']?></td>
        <td><?= $product['created_at']?></td> 
        
        <td>
            <a class="btn btn-info m-1" href="?do=show&productID=<?= $product['product_id']?>" title="Show">
                <i class="fas fa-eye"></i>
            </a>
            <a class="btn btn-warning m-1"  href="?do=edit&productID=<?= $product['product_id']?>" title="Edit">
            <i class="fas fa-edit"></i>
            </a>
            <a class="btn btn-danger m-1" href="?do=delete&productID=<?= $product['product_id']?>" title="Delete">
            <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach?>
</tbody>
</table>
    </div>
        <!--End all members page-->

    <?php elseif($do == "add"):?>
        
        <div class="container">
            <h1 class="text-center">Add Product</h1>
            <form method="post" action="?do=insert">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Product Name</label>
        <input type="text" class="form-control" name="productName">
    </div>
    <div class="mb-3">
        <label class="form-label">Product Category</label>
        <input type="text" class="form-control" name="productCategory">
    </div>
    <div class="mb-3">
        <label class="form-label">Product Discount</label>
        <input type="text" class="form-control" name="productDiscount">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    
</form>
</div>
<a href="products.php" class="btn btn-dark m-2">Back</a>
        
    <?php elseif($do == "insert"):?>
        <?php 
            if($_SERVER['REQUEST_METHOD'] == "POST")
            {
                $productName = $_POST['productName'];
                $productCategory = $_POST['productCategory'];
                $productDiscount = $_POST['productDiscount'];
                $stmt = $con -> prepare("INSERT INTO products (product_name , product_category , product_discount , created_at) VALUES ( ? , ? , ? , now())");
                $stmt -> execute(array($productName,$productCategory,$productDiscount));
                header("location:products.php?do=add");
            }
        ?>
    <?php elseif($do == "edit"):?>
        <?php 
            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? intval($_GET['productID']) : 0;
            $stmt = $con -> prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt -> execute(array($productID));
            $product = $stmt->fetch();
            $count = $stmt -> rowCount();
        ?>
        <?php if($count == 1):?>
            <div class="container">

            <h1 class="text-center">Edit Product</h1>

            <form method="post" action="?do=update">
    <div class="mb-3">
    <input type="hidden" class="form-control" value="<?= $product['product_id']?>" name="productID">
    <label for="exampleInputEmail1" class="form-label">Product Name</label>
    <input type="text" class="form-control" value="<?= $product['product_name']?>" name="productName">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Category</label> 
    <input type="text" class="form-control" value="<?= $product['product_category']?>" name="productcategory">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Price</label> 
    <input type="text" class="form-control" value="<?= $product['product_discount']?>" name="productdiscount">
</div>

<button type="submit" class="btn btn-primary">Update</button>
</form>
</div>
        <?php endif?>

        <?php elseif($do == "update"):?>
            <?php 
                if($_SERVER['REQUEST_METHOD'] == "POST")
                {
                    $productID =$_POST['productID'];
                    $productName =$_POST['productName'];
                    $productCategory =$_POST['productcategory'];
                    $productPrice =$_POST['productprice'];
                    $stmt = $con -> prepare("UPDATE products SET product_name=? , product_category=? , product_price=? WHERE product_id=?");
                    $stmt -> execute(array($productName , $productCategory , $productPrice , $productID));
                    header("location:products.php");
                }
            ?>
        
        <?php elseif($do == "delete"):?>
            <?php
                $productID = $_GET["productID"];
                $stmt = $con -> prepare("DELETE FROM products WHERE product_id=?");
                $stmt -> execute(array($productID));
                header("location:products.php");
            ?>
        <?php elseif($do == "show"):?>
            <?php 
                $productID = $_GET["productID"];
                $stmt = $con -> prepare("SELECT * FROM products WHERE product_id=?");
                $stmt -> execute(array($productID));
                $product = $stmt->fetch();
                echo"<pre>";
                print_r($product);
                echo"</pre>";
            ?>
        <?php 
                $productDiscount = $_GET["productDiscount"];
                $stmt = $con -> prepare("SELECT * FROM products WHERE product_discount=?");
                $stmt -> execute(array($productDiscount));
                $product = $stmt->fetch();
                echo"<pre>";
                print_r($product);
                echo"</pre>";
            ?>
        <a href="products.php" class="btn btn-dark m-2">Back</a>
        <?php endif?>

        <?php include "resources/includes/footer.php"?>
        <?php else:?>
            <?php header("location:index.php")?>
        <?php endif?>