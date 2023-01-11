<?php
// include common file
include("library/system/common.php");
require_once(USER_LIBRARY_PATH."Product_Controller.php");
$product_controller = new Product_Controller();
require_once(USER_LIBRARY_PATH."Cart_Controller.php");
$cart_controller = new Cart_Controller();
$agent = $helper_controller->get_user_agent();
/* get wholesale customer id from URL and store in session */
if (isset($_GET["customerid"])) {
    require_once(USER_LIBRARY_PATH."Customer_Controller.php");
    $customer_controller = new Customer_Controller();
    $customer_controller->is_valid_wholesale_customer_id($_GET["customerid"]); 
    $customer_in_session = true;
?>
    <script>
        var trigger_wcn_discount = true;
    </script>
<?php }

// default filters

if(!isset($_GET['features'])){
	$_GET['features'] = "chevron_planks";
}

// get products
$products = $product_controller->get_flooring($_GET);

if(isset($_GET))
{
    $sortby_filter=trim($_GET['sortby']);
}
$color_group_array=$product_controller->get_color_groups(2);
$product_dimensions=$product_controller->get_product_dimensions(2);
if(is_array($product_dimensions) && count($product_dimensions)>0) {
    $wear_thickness_array = array();
    $length_array = array();
    $width_array = array();
    $thickness_array = array();
    foreach ($product_dimensions as $product) {
        // store product wear thickness in array
        $wear_thickness_array[] = $product['wear_layer'];
        // store product length in array
        $length_array[] = $product['length'];
        // store product width in array
        $width_array[] = $product['width'];
        // store product thickness in array
        $thickness_array[] = $product['thickness'];
    }
}
// wear layer thickness
$wear_thickness_array = array_unique($wear_thickness_array);
// plank length
$plank_length_array = array_unique($length_array);
if(is_array($plank_length_array) && count($plank_length_array) > 0) {
    $new_plank_length_array = array();
    foreach ($plank_length_array as $plank_length) {
        $result = explode('(', $plank_length);
        $new_plank_length_array[] = trim($result['0']);
    }
}
$plank_length_array = array_unique($new_plank_length_array);
array_multisort($plank_length_array, SORT_DESC, SORT_STRING);

// plank width
$plank_width_array = array_unique($width_array);
array_multisort($plank_width_array, SORT_DESC, SORT_STRING);

// plank thickness
$plank_thickness_array = array_unique($thickness_array);
$plank_thickness_array = array_values($plank_thickness_array);
$temp = $plank_thickness_array[0];
$plank_thickness_array[0] = $plank_thickness_array[1];
$plank_thickness_array[1] = $temp;
$temp = $plank_thickness_array[2];
$plank_thickness_array[2] = $plank_thickness_array[3];
$plank_thickness_array[3] = $temp;

// get product ids in cart
$product_ids_in_cart_array = array();
$products_ids = $cart_controller->get_product_ids_from_cart($_SESSION['login_id']);
if(is_array($products_ids) && count($products_ids) > 0) {
    foreach ($products_ids as $products_id) {
        $product_ids_in_cart_array[] = $products_id['prod_id'];
    }
}
// get sample ids in cart
$product_sample_in_cart_array = array();
$product_sample_length_array = array();
$sample_ids = $cart_controller->get_sample_ids_from_cart($_SESSION['login_id']);
if(is_array($sample_ids) && count($sample_ids) > 0) {
    foreach ($sample_ids as $sample_id) {
        $product_sample_in_cart_array[] = $sample_id['prod_id'];
        $product_sample_length_array[$sample_id['prod_id']."-".$sample_id['sample_length']] = $sample_id['quantity'];
    }
}
// get browser
$browser = $helper_controller->get_browser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <?php include(BASE_PATH.'meta-tags.php'); ?>
    <!-- preload late discovered hero images faster -->
    <link rel="preload" as="image" href="<?php echo IMAGE_PATH;?>bamboo-flooring-cost/malted-ale-eucalyptus-kitchen-400.jpg" media="(max-width: 599px)">
    <link rel="preload" as="image" href="<?php echo IMAGE_PATH;?>bamboo-flooring-cost/malted-ale-eucalyptus-kitchen-1024.jpg" media="(min-width: 600px) and (max-width: 1024px)">
    <link rel="preload" as="image" href="<?php echo IMAGE_PATH;?>bamboo-flooring-cost/malted-ale-eucalyptus-kitchen-1600.jpg" media="(min-width: 1025px) and (max-width: 1600px)">
    <link rel="preload" as="image" href="<?php echo IMAGE_PATH;?>bamboo-flooring-cost/malted-ale-eucalyptus-kitchen-1920.jpg" media="(min-width: 1601px)">
    <!-- styles -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700&display=swap" as="style">
    <?php echo $helper_controller->include_css('bootstrap/ambient-bootstrap.min.css,ambient.css,responsive/ambient-responsive.css,bamboo-flooring-cost.css,responsive/bamboo-flooring-cost-responsive.css') ;?>
    <?php echo $helper_controller->include_css('bootstrap/ambient-bootstrap.min.css,ambient.css,responsive/ambient-responsive.css,plp.css,spc-luxury-vinyl-flooring.css,responsive/spc-luxury-vinyl-flooring-responsive.css') ;?>
    <?php echo $helper_controller->include_css('flexslider/flexslider.min.css,alertify/alertify.min.css','plp-styles'); ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style><?php include("css/coalition/custom.css"); ?></style>
</head>
<body>
    <!-- header -->
    <?php include("header.php"); ?>
    <!-- wrapper -->
    <div class="wrapper">
        <!-- top section -->
        <section id="top-section" class="section">
            <div class="container container-lg">
                <div class="section-content">
                    <h1 class="page-title">Bamboo Flooring</h1>
                    <p style="color:white;">At Ambient&reg;, we offer a wide selection of quality flooring made from bamboo that is all natural and built to last. With more than 30 different colors and styles available, our bamboo wood flooring will look fantastic in any home or commercial space. Natural bamboo flooring offers a balance between beauty and strength that is perfect for high traffic areas.</p>
                    <div class="btn-container">
                        <button class="btn goto-products-btn" type="button">Go To Products</button>
                    </div>
                </div>
            </div>
        </section>
        <!-- features section -->
        <section id="features-section" class="section">
            <div class="container container-lg">
                <div class="section-header text-center">
                    <h2>Features of Bamboo Flooring</h2>
                </div>
                <div class="section-content">
                    <div class="features-grid">
                        <div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/hand-gesture.png" alt="waterproof" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>SUSTAINABLE</h3>
                                    <p>Our flooring is made from Moso bamboo plants that are harvested every 5-7 years.</p>
                                </div>
                            </div>
                        </div>
						<div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon  icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/clean.png" alt="no formaldehyde" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>EASY TO CLEAN</h3>
                                    <p>A cost-effective alternative to traditional hardwood.</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/shield.png" alt="no formaldehyde" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>EXTREMELY DURABLE</h3>
                                    <p>The only hardwood that stands up to pets, kids and active families.</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/family.png" alt="easy installation" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>SAFE FOR YOUR FAMILY</h3>
                                    <p>Our floors meet the highest indoor air quality standards and are Floorscore® certified.</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/shield.png" alt="protective layer" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>LONG-LASTING</h3>
                                    <p>Our bamboo flooring can be refinished at least twice, but it rarely needs refinishing.</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/hourglass.png" alt="swatches" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>GREAT FOR DIY’ERS:</h3>
                                    <p>We offer click-lock floating & engineered flooring with easy installation guides.</p>
                                </div>
                            </div>
                        </div>
						<div class="grid__child">
                            <div class="feature-box">
                                <div class="feature-box-icon icon-fix">
                                    <img data-src="<?php echo IMAGE_PATH;?>assets/features/hammer.png" alt="family" class="feature-image lazyload" width="42" height="42">
                                </div>
                                <div class="feature-box-body">
                                    <h3>VERSATILE</h3>
                                    <p>Its flexibility and durability make this flooring a great choice for any room in the house.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- bamboo flooring cost section -->
        <!-- <section id="section-one" class="bamboo-collection-text">
            <div class="container bamboo-collection-text-container">
                <div class="section-one-content">
                        <h2>Browse our collection of Bamboo Flooring products</h2>
                    <p >Bamboo hardwoods in 30+ colors and styles including strand woven, handscraped and engineered bamboo flooring, as well as click-lock floating and tongue-and-groove flooring options. The unique marbled grain of strand woven bamboo floors brings an organic warmth and beauty to every room.</p>
                </div>
            </div>
        </section> -->

        <div id="bamboo-flooring-cost-section" class="section bamboo-flooring-cost-section section-bg">
            <div class="container-fluid">
                <!-- breadcrumb -->
                <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li itemprop="itemListElement" itemscope
          itemtype="https://schema.org/ListItem">
                        <a itemprop="item" href="<?php echo $helper_controller->site_url(); ?>">
                            <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li itemprop="itemListElement" itemscope
          itemtype="https://schema.org/ListItem" class="active">
                        <span itemprop="name">Bamboo Flooring</span>
                        <meta itemprop="position" content="2" />
                    </li>
                </ol>
                <div class="fix1">
                    <?php if($agent != 'android' && $agent != 'iphone'){ ?>
                    <div class="filters hidden-xs hidden-sm hidden-md">
                        <!-- sku filter -->
                        <div id="sku-filter" class="filter">
                            <div class="filter-heading">
                                Search By Item No
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <div class="filter search-filter">
                                    <label id="searchFilter" for="sku_search" style="display: none;">Filter</label>
                                    <input type="text" aria-labelledby="searchFilter" id="sku_search" name="sku_search" class="form-control" placeholder="Item Number">
                                    <button class="btn btn-default" type="button">Go</button>
                                </div>
                            </div>
                        </div>
                        <div class="filter-by">Filter By</div>
                        <!-- sort by filter -->
                        <div id="sortby-filter" class="filter">
                            <div class="filter-heading">
                                Sort By
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <li>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="sort_by[]" value="color" checked>
                                                <span class="filter-name">Color</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="sort_by[]" value="price" <?php if($sortby_filter=='price'){echo 'checked';}?>>
                                                <span class="filter-name">Price</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- species filter -->
                        <div id="species-filter" class="filter">
                            <div class="filter-heading">
                                Flooring Type
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_type[]" value="bamboo">
                                                <span class="filter-name">Bamboo</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_type[]" value="eucalyptus">
                                                <span class="filter-name">Eucalyptus</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- surface style filter -->
                        <div id="surface-style-filter" class="filter">
                            <div class="filter-heading">
                                Surface Style
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="handscraped">
                                                <span class="filter-name">Handscraped/Distressed</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="flat_smooth">
                                                <span class="filter-name">Flat/Smooth</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- features filter -->
                        <div id="features-filter" class="filter">
                            <div class="filter-heading">
                                Features
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="floating_tab">
                                                <span class="filter-name">Click-Lock (Floating)</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                            <input type="checkbox" name="floor_features[]" value="tg_tab">
								            <span class="filter-name">Solid T&G (nail or glue down)</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="engineered-feature">
                                                <span class="filter-name">Engineered</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="waterresistant">
                                                <span class="filter-name">Water Resistant</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="basements">
                                                <span class="filter-name">For Basements</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="kitchen_friendly">
                                                <span class="filter-name">Kitchens &amp; Pet Friendly</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="high_traffic">
                                                <span class="filter-name">High Traffic</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="heat">
                                                <span class="filter-name">Radiant Heat Safe</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input <?php if(isset($_GET['features'])){if($_GET['features'] == "chevron_planks"){echo "checked";}} ?> type="checkbox" name="floor_features[]" value="chevron_planks">
                                                <span class="filter-name">Chevron Planks</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="floor_features[]" value="bspc-rigid-core-bamboo">
                                                <span class="filter-name">SPC Rigid Core</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- plank length filter -->
                        <div id="plank-length-filter" class="filter">
                            <div class="filter-heading">
                                Plank Length
                                <i class="fa fa-info-circle filter-info-icon" aria-hidden="true" data-trigger="hover" data-placement="top" data-original-title="Plank Length" data-content="All of our floors come in single-length planks (no random lengths)"></i>
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <?php
                                    if(is_array($plank_length_array) && count($plank_length_array) > 0) {
                                        foreach ($plank_length_array as $plank_length) {
                                            if (!empty($plank_length)) {
                                                echo '<li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="plank_length[]" value="'.$plank_length.'">
                                                                <span class="filter-name">'.$plank_length.'</span>
                                                            </label>
                                                        </div>
                                                    </li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- plank width filter -->
                        <div id="plank-width-filter" class="filter">
                            <div class="filter-heading">
                                Plank Width
                                <i class="fa fa-info-circle filter-info-icon" aria-hidden="true" data-trigger="hover" data-placement="top" data-original-title="Plank Width" data-content="All of our floors come in single-width planks (no random widths)"></i>
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <?php
                                    if(is_array($plank_width_array) && count($plank_width_array) > 0) {
                                        foreach ($plank_width_array as $plank_width) {
                                            if (!empty($plank_width)) {
                                                echo '<li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="plank_width[]" value="'.$plank_width.'">
                                                                <span class="filter-name">'.$plank_width.'</span>
                                                            </label>
                                                        </div>
                                                    </li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- plank thickness filter -->
                        <div id="plank-thickness-filter" class="filter">
                            <div class="filter-heading">
                                Plank Thickness
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <?php
                                    if(is_array($plank_thickness_array) && count($plank_thickness_array) > 0) {
                                        foreach ($plank_thickness_array as $plank_thickness) {
                                            if (!empty($plank_thickness)) {
                                                echo '<li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="plank_thickness[]" value="'.$plank_thickness.'">
                                                                <span class="filter-name">'.$plank_thickness.'</span>
                                                            </label>
                                                        </div>
                                                    </li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- wear layer thickness filter -->
                        <div id="wear-thickness-filter" class="filter">
                            <div class="filter-heading">
                                Wear Layer Thickness
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <?php
                                    if(is_array($wear_thickness_array) && count($wear_thickness_array) > 0) {
                                        foreach ($wear_thickness_array as $wear_thickness) {
                                            if (!empty($wear_thickness)) {
                                                echo '<li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="wear_thickness[]" value="'.$wear_thickness.'">
                                                                <span class="filter-name">'.$wear_thickness.'</span>
                                                            </label>
                                                        </div>
                                                    </li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>                        
                        <!-- color group filter -->
                        <div id="color-group-filter" class="filter">
                            <div class="filter-heading">
                                Color Group
                                <span class="expand-collapse-icon">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <div class="filter-options">
                                <ul>
                                    <?php
                                    if(is_array($color_group_array) && count($color_group_array) > 0) {
                                        foreach ($color_group_array as $color_group) {  
                                            if (!empty($color_group['color_group'])) {
                                                echo '<li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="color_group[]" value="'.$color_group['color_group'].'">
                                                                <span class="filter-name">'.$color_group['color_group'].'</span>
                                                            </label>
                                                        </div>
                                                    </li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>                                                
                    </div>
                    <?php } ?>
                </div>
                <div class="fix2">
                    <!-- flooring free shipping banner -->
					<a href="javascript:void(0)" class="flooring-free-shipping-banner" data-toggle="modal" data-target="#free-shipping-modal">
                        <div class="flooring-free-shipping-banner-content">
                            <img data-src="<?php echo IMAGE_PATH;?>product/delivery-truck.png" alt="delivery truck" class="lazyload" width="128" height="73">
                            <span class="yellow-text">Free shipping <span class="hidden-xs">on all flooring orders</span> <span class="visible-xs"></span>over 600 sq ft *</span>
                            <span class="white-text"><strong> (Terms & conditions apply)</strong></span>
                        </div>
                    </a>
					<!-- Free Shipping Modal -->
					<div id="free-shipping-modal" class="modal fade site-modal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h4>Free Shipping Terms &amp; Conditions</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<img data-src="<?php echo IMAGE_PATH; ?>assets/modal-close-icon.png" alt="modal close icon" width="49" height="53" data-dismiss="modal" class="lazyload">
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											All shipping costs and shipping promotions quoted on our website (including free shipping) are valid only to the contiguous 48 United States, and these promotions may not apply if your delivery address is located on an island, or in a rural, high density urban, or low-volume freight shipping area.  If your delivery location is determined to fall within one of these areas, this website may add an additional shipping surcharge.  Please note that it is the freight companies that determine which areas fall within these categories, and they increase their rates accordingly. While Ambient may still subsidize a large portion of the shipping costs, the remainder of the increased charges are the responsibility of the customer.  These surcharges are always displayed by our website upfront before you finalize your transaction so the customer can accept the charges and continue, or decide not to purchase.			
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <!-- applied filters -->
                    <div class="applied-filters"></div>
                    <!-- products -->
                    <div id="products-listing" class="products category-flooring">
                        <?php
                        if(is_array($products) && count($products)>0) {
                            $length_array = array();
                            $width_array = array();
                            $thickness_array = array();
                            $i = 1;
                            foreach ($products as $product) {
								// get product specification
                                $product_specification = $product_controller->get_product_specification($product['id']);
								
                                //if(($agent == 'android' || $agent == 'iphone') && $i > 4){
                                //    break;
                                //}
                                // store product length in array
                                $length_array[] = $product['length'];
                                // store product width in array
                                $width_array[] = $product['width'];
                                // store product thickness in array
                                $thickness_array[] = $product['thickness'];
                                if(stripos($product_specification['flooring_species'],'eucalyptus')!== false)
                                {
                                    $product_url=$helper_controller->site_url('eucalyptus-flooring/'.$product['product_url']);
                                }
                                else
                                {
                                    $product_url=$helper_controller->site_url('products/'.$product['product_url']);
                                }
                        ?>
                                <!-- product -->
                                <div class="product" data-product-id="<?php echo $product['id']; ?>" data-product-category="<?php echo $product['cat_id'];?>">
                                    <!-- product image container -->
                                    <a href="<?php echo $product_url; ?>" class="product-image-container" aria-label="Go to <?php echo $product['name']; ?> Details">
                                        <!-- limited stock -->
                                        <?php
                                        $stock = intval($product['quantity']) * intval($product['sq_feet']);
                                        if($stock < 800) {
                                            //echo '<div class="limited-stock-tag">Limited Stock</div>';
                                        }
                                        ?>
                                        <!-- sale -->
                                        <?php
                                        if($product['sales_price'] != '' && !$is_customer_discount_enabled) {
                                            echo '<div class="sale-tag">On Sale!</div>';
                                        }
                                        ?>
                                        <!-- free shipping, on sale, clearance -->
                                        <?php 
                                        if ($product['free_ship_tag'] == 'TRUE' || $product['free_ship_tag'] == '1') {
                                            echo '<div class="clearance-tag">Free Shipping!</div>';
                                        } else if ($product['prod_clearance'] == 'TRUE' || $product['prod_clearance'] == '1') {
                                            echo '<div class="clearance-tag">Clearance!</div>';
                                        }
                                        ?>
                                        <!-- product mask -->
                                        <div class="product-mask">
                                            <div class="product-mask-content">
                                                <p>PHOTOS & DETAILS</p>
                                                <div class="btn btn-default go-now-button">
                                                    Go Now
                                                    <i class="fa fa-angle-double-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product image -->
                                        <?php
                                        $product_image = $helper_controller->get_product_image('2', $product['product_image'], 'listing_thumbnail',$product['id']);
                                        ?>
                                        <img data-src="<?php echo $product_image; ?>" alt="<?php echo $helper_controller->alt_tag($product_image); ?>" class="lazyload">
                                        <!-- product image buttons -->
                                        <div class="product-image-action-buttons">
                                            <ul>
                                                <li class="see-photos">
                                                    <div class="btn view-photo-button" data-id="<?php echo $product['id'];?>" data-category-id="<?php echo $product['cat_id'];?>">
                                                        <i class="fa fa-photo" aria-hidden="true"></i>
                                                        See Photos
                                                    </div>
                                                </li>
                                                <?php if($product["video_path"] != '' && $browser['name'] != 'Apple Safari') { ?>
                                                <li class="play-video">
                                                    <div class="btn play-video-button" data-url="//www.youtube.com/embed/<?php echo $product["video_path"]; ?>&autoplay=1">
                                                        <i class="fa fa-play" aria-hidden="true"></i>
                                                        Play Video
                                                    </div>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </a>
                                    <!-- product description -->
                                    <div class="product-detail">
                                        <h4 class="product-title"><?php echo $product['name']; ?>
										<?php                   
                                        if(stripos($product['flooring_species'],'SPC')!==false)
                                        {
                                            if($product_specification['flooring_group']=='Click Lock BSPC')
                                            {
                                                echo '<br/><span class="product-title-bspc">Rigid Core Bamboo</span>';
                                            }
                                            else
                                            {
                                                echo '<br/><span class="product-title-not-bspc">Rigid Core LVP</span>';
                                            }       
                                        }
                                        if(stripos($product['flooring_species'],'Dryback')!==false)
                                        {
                                            echo '<br/><span class="product-title-dryback">Dryback Flexible LVP</span>';
                                        }
                                        if(stripos($product['flooring_species'],'Bamboo')!==false && $product_specification['flooring_group']!='Click Lock BSPC')
                                        {
                                            echo '<br/><span class="product-title-bamboo">Bamboo</span>';
                                        }
                                        if(stripos($product['flooring_species'],'Eucalyptus')!==false)
                                        {
                                            echo '<br/><span class="product-title-eucalyptus">Eucalyptus</span>';
                                        }
                                        ?>
										</h4>
                                        <!-- product price -->
                                        <?php if(!$helper_controller->is_restricted_location()){?>
                                        <div class="product-price">
                                            <?php if($is_customer_discount_enabled){
                                            ?>
											<div class="product-price-number price_hide">
												<?php
												echo '<span class="price-label">Stand Retail Price:</span>
													$'.$helper_controller->product_price($product['price']).'
													<span class="price_type">per '.strtolower($product["price_type"]).'</span>';
												echo '<div class="product-discounted-price-number product-price-number">
														<span class="price-label">Your Custom Wholesale Price:</span>
														$'.$helper_controller->product_price($product['sales_price']).'
														<span class="price_type">per '.strtolower($product["price_type"]).'</span>
													</div>';
												?>
											</div>
                                            <?php } else { ?>
                                            <div class="product-price-number price_hide">
                                                <?php
                                                if($product['sales_price'] != '') {
                                                    echo '<span class="strike-container">';
                                                        echo '<span class="sales_price">';
                                                            echo '$'.$helper_controller->product_price($product['price']);
                                                        echo '</span>'; 
                                                    echo '</span>';
                                                    echo '$'.$helper_controller->product_price($product['sales_price']);
                                                    echo '<span class="price_type">per '.strtolower($product["price_type"]).'</span>';
                                                } else {
                                                    echo '$'.$helper_controller->product_price($product['price']);
                                                    echo '<span class="price_type">per '.strtolower($product["price_type"]).'</span>';
                                                }
                                                ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                        <!-- product description -->
                                        <div class="product-description">
                                            <?php
                                            $flooring_group_array = $helper_controller->get_flooring_group();
                                            // get product specification
                                            $product_specification = $product_controller->get_product_specification($product['id']);
                                            ?>
                                            <p class="product-description-style-blue"><?php echo $flooring_group_array[$product_specification['flooring_group']]?></p>
                                            <p class="product-description-style-green">Installation: <?php echo $product_specification['installation']?></p>
                                        </div>
                                    </div>
									<div class="product-samples row product-buttons">
										<div class="wrap">
											<label aria-hidden="true" data-content="<?php echo (!$is_customer_discount_enabled)?'Limit of 5 free samples and one shipment per household. Additional samples are $1 each.':'Limit of 20 free samples or one shipment per household. Additional samples are $1.75 each.'?>">4" Sample <span class="green">(FREE)</span></label>
											<div class="update-product-quantity">
												<div class="input-group">
													<span class="input-group-btn">
														<button type="button" class="btn btn-danger decrease-matching-product" aria-label="decrease quantity">
															<i class="fa fa-minus"></i>
														</button>
													</span>
													<input type="text" name="quantity" class="form-control input-number matching-product-quantity" value="<?php echo (array_key_exists($product['id'].'-4',$product_sample_length_array)?$product_sample_length_array[$product['id'].'-4']:'0')?>" minlength="1" maxlength="100" data-sample-length="4" aria-label="Quantity">
													<span class="input-group-btn">
														<button type="button" class="btn btn-success increase-matching-product" aria-label="increase quantity">
															<i class="fa fa-plus"></i>
														</button>
													</span>
												</div>
											</div>
										</div>
										<div class="wrap">
											<label>8" Sample <span class="blue">($2)</span></label>
											<div class="update-product-quantity">
												<div class="input-group">
													<span class="input-group-btn">
														<button type="button" class="btn btn-danger decrease-matching-product" aria-label="decrease quantity">
															<i class="fa fa-minus"></i>
														</button>
													</span>
													<input type="text" name="quantity" class="form-control input-number matching-product-quantity" value="<?php echo (array_key_exists($product['id'].'-8',$product_sample_length_array)?$product_sample_length_array[$product['id'].'-8']:'0')?>" minlength="1" maxlength="100" data-sample-length="8" aria-label="Quantity">
													<span class="input-group-btn">
														<button type="button" class="btn btn-success increase-matching-product" aria-label="increase quantity">
															<i class="fa fa-plus"></i>
														</button>
													</span>
												</div>
											</div>
										</div>
										<div class="wrap">
											<label>12" Sample <span class="blue">($4)</span></label>
											<div class="update-product-quantity">
												<div class="input-group">
													<span class="input-group-btn">
														<button type="button" class="btn btn-danger decrease-matching-product" aria-label="decrease quantity">
															<i class="fa fa-minus"></i>
														</button>
													</span>
													<input type="text" name="quantity" class="form-control input-number matching-product-quantity" value="<?php echo (array_key_exists($product['id'].'-12',$product_sample_length_array)?$product_sample_length_array[$product['id'].'-12']:'0')?>" minlength="1" maxlength="100" data-sample-length="12" aria-label="Quantity">
													<span class="input-group-btn">
														<button type="button" class="btn btn-success increase-matching-product" aria-label="increase quantity">
															<i class="fa fa-plus"></i>
														</button>
													</span>
												</div>
											</div>
										</div>
									</div>
                                    <!-- product buttons -->
                                    <div class="product-buttons">
                                        <!-- free sample button -->
                                        <!-- check if sample is already added in cart -->
                                        <?php if(in_array($product['id'], $product_sample_in_cart_array)) { ?>
                                                <button type="button" class="btn btn-default cancel_sample" data-product-id="<?php echo $product['id']; ?>" data-product-category="<?php echo $product['cat_id'];?>">SAMPLE IN CART</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-default free-sample-button<?php echo (!empty($product['disallow_sample']))?' disallow-sample':''?>" data-product-id="<?php echo $product['id']; ?>" data-product-category="<?php echo $product['cat_id'];?>" data-next-available="<?php echo ($product['next_available_date'] != '0000-00-00')?date('m/d/Y',strtotime($product['next_available_date'])):"" ?>">
                                                <i class="fa fa-plus"></i>
                                                Get A Sample
                                            </button>
                                        <?php } ?>
                                        <a href="<?php echo $product_url;?>" class="btn btn-default buy-now-button" aria-label="Buy Now - <?php echo $product['name']; ?>">
                                            <i class="fa fa-shopping-cart"></i>
                                            Buy Now
                                        </a>
                                    </div>
                                </div>
                        <?php
                                $i++;
                            }
                        }
                        else {
    echo "<h3>That item number does not exist</h3>";
}
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <!-- Include SPC Luxury Vinyl PLP Info -->
<section id="product-description" class="container">
	<div>
		<div class="section-two-content">
			<div class="row second-row">
				<div class="col-md-6">
					<h2 class="section-title">What is Bamboo Wood Flooring?</h2>
					<p>Bamboo flooring is made from rapidly renewable Moso bamboo that is organically grown and known for its strength and elasticity. All our floors are coated with super-tough <a href="https://www.ambientbp.com/best-bamboo-flooring-finish.php">proprietary Accuseal Ultra&reg; finish</a> and backed by an industry-leading lifetime structural and finish warranty. We strive to ensure that our products meet only the highest standards in milling, appearance and <a href="https://www.ambientbp.com/urea-formaldehyde-free-bamboo-flooring.php">safety</a>. Our natural bamboo flooring is Floorscore&reg; certified and meets the CARB Phase 2 standard which is the most stringent indoor air quality standard in the world. All our bamboo wood flooring comes in premium widths (mostly wide planks) and lengths. Plus, you can get free samples before you purchase because we want you to be more than confident that the color you chose is perfect for your space.</p>
				</div>
				<div class="col-md-6 video-img">
					<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/two-people-standing-on-wooden-floor.png" alt="two people standing on wooden floor" class="lazyload">
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Strong and sustainable -->
<section id="product-description" class="container">
	<div >
		<div class="section-two-content">
			<div class="row first-row">
				<div class="col-md-12">
					<h2 class="section-title">Flooring That is Better for the Environment</h2>
					<p class="">Making a responsible choice for the environment doesn&rsquo;t mean having to sacrifice quality or style. The unique marble grain of strand bamboo and <a href="https://www.ambientbp.com/eucalyptus-flooring">eucalyptus flooring</a> is beautiful and distinctive. Our floors have been used in a multitude of settings and will match with any decor to add a classic or contemporary accent that never goes out of style!&nbsp;</p>
                    <p class="">Bamboo flooring is much harder than traditional hardwood floors so they are resistant to denting, scratching, and almost anything else your family can throw at them. Plus, they are incredibly <a href="https://www.ambientbp.com/blog/is-bamboo-flooring-waterproof">water-resistant</a> so when your toddler spills you can clean them up first with no worries!</p>
				</div>
			</div>
			<div class="row second-row">
				<div class="col-md-6">
					<h3 style="font-weight: bold; margin-bottom: 20px;">What Makes Ambient® Different?</h3>
					<div class="additional-content custom">
						<ul class="tools-instructions">
							<li><strong>30 Day Money Back Guarantee:</strong> All products come with a 30-day money back guarantee, so if you&rsquo;re not satisfied, you&rsquo;ll get a full refund.</li>
							<li><strong>Life-Time Warranty: </strong>Our bamboo flooring comes with a structural and finish life-time guarantee.</li>
							<li><strong>Eco-Friendly:</strong> We offer <a href="https://www.ambientbp.com/all-ambient-flooring.php">sustainable flooring</a> options that are good for the environment.</li>
							<li><strong>Family Friendly:</strong> Our flooring is pet and child friendly and our finishes meet the strictest indoor air standards.</li>
                            <li><strong>Free Shipping Over 600 sq ft:</strong> We are dedicated to client satisfaction and will do everything possible to make your experience impeccable.</li>
						</ul>
					</div>
				</div>
				<div class="col-md-6 video-img">
					<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/four-legged-chair-on-wooden-floor.png" alt="four legged chair on wooden floor" class="lazyload">
				</div>
			</div>
		</div>
	</div>
</section>

<!-- benefits sectionn -->
<section id="benefits-section" class="section">
	<div class="container container-lg">
		<div class="section-content">
			<div class="row">
				<div class="col-md-4">
					<div class="features-box-right">
						<div class="features-icon-right">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/hammer.png" width="50" height="50" alt="hammer" class="benefit-icon lazyload">
						</div>
						<h3>Best Floors For Pets & Kids</h3>
						<p>Testing shows that strand bamboo is the hardest wood on the market, and stands up to pets and kids.</p>
					</div>
					<div class="features-box-right">
						<div class="features-icon-right">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/renewable.png" width="50" height="50" alt="renewable" class="benefit-icon lazyload">
						</div>
						<h3>Environmentally Friendly</h3>
						<p>Made from a rapidly renewable resource, re-harvested every 5 years versus 40-80 years for hardwoods.</p>
					</div>
					<div class="features-box-right">
						<div class="features-icon-right">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/refinishing.png" width="50" height="50" alt="refinishing" class="benefit-icon lazyload">
						</div>
						<h3>Can Be Refinished</h3>
						<p>Our floors can be refinished at least twice although it's rarely needed in a residential setting.</p>
					</div>
					<div class="features-box-right">
						<div class="features-icon-right">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/broom.png" width="50" height="50" alt="broom" class="benefit-icon lazyload">
						</div>
						<h3>Easy To Clean & Maintain</h3>
						<p>Bamboo is easy to clean, cutting down on allergens and dirt. With 24-hour spill protection, it's great for kitchens.</p>
					</div>
				</div>
				<div class="col-md-4 hidden-sm hidden-xs text-center">
					<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/bamboo.png" width="288" height="470" class="bamboo-image lazyload" alt="bamboo">
				</div>
				<div class="col-md-4">
					<div class="features-box-left">
						<div class="features-icon-left">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/forest.png" width="50" height="50" alt="forest" class="benefit-icon lazyload">
						</div>
						<h3>A Unique Hardwood Floor</h3>
						<p>Bamboo has a distinctive and unique marbled grain that is beautiful, inviting, and warm underfoot. </p>
					</div>
					<div class="features-box-left">
						<div class="features-icon-left">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/piggy-bank.png" width="50" height="50" alt="piggy bank" class="benefit-icon lazyload">
						</div>
						<h3>A Great Value</h3>
						<p>All of Ambient's floors come in wide planks and premium lengths and cost just $3-$6 per square foot delivered, about half of comparable hardwoods.</p>
					</div>
					<div class="features-box-left">
						<div class="features-icon-left">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/ribbon.png" width="50" height="50" alt="ribbon" class="benefit-icon lazyload">
						</div>
						<h3>Safe For Your Family</h3>
						<p>Ambient® finishes meet the strictest indoor air standards in the world, such as CARB Phase 2.</p>
					</div>
					<div class="features-box-left">
						<div class="features-icon-left">
							<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-leed-credits/versatility.png" width="50" height="50" alt="versatility" class="benefit-icon lazyload">
						</div>
						<h3>Versatility</h3>
						<p>Available in styles like click-lock and T&G, can be installed <b>over concrete, in basements, and more</b>.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- bamboo flooring cheat sheet section -->
<div id="bamboo-flooring-cheat-sheet-section" class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-3 bamboo-flooring-cheat-sheet-fix1">
				<img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/checklist.jpg" alt="checklist" class="bamboo-flooring-cheat-sheet-image lazyload" width="120" height="120">
			</div>
			<div class="col-sm-9 bamboo-flooring-cheat-sheet-fix1">
				<div class="heading"><h2>Flooring Cheat Sheet</h2></div>
				<p>Download our handy-dandy flooring cheatsheet PDF to compare features like pricing, shipping, warranty, indoor air quality, hardness ratings, and more!</p>
				<a href="<?php echo $helper_controller->site_url('pdf/BambooFlooringCheatSheet.pdf'); ?>" class="btn btn-lg download-pdf-button" download target="_blank">Download <span class="hidden-xs">Cheat Sheet</span> PDF</a>
			</div>
		</div>
	</div>
</div>

<!-- great articles section -->
<section id="great-articles-section" class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h2>More Great Information About Bamboo Floors</h2>
            <p>Find the answers to common questions and discover more benefits of bamboo by visiting our blog.</p> 
        </div>
        <div class="section-content">
            <div class="row">
                <div class="col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('types-of-bamboo-flooring-solid-vs-click.php'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/types-of-bamboo-flooring.jpg" alt="types of bamboo flooring" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/site-logo.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Ambient
                                <span class="article-date">/ 16 Oct 2018</span>
                            </div>
                            <div class="article-title">The 5 Types of Bamboo Flooring</div>
                            <p>Bamboo floors come in a variety of construction and installation types...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('strand-woven-bamboo-flooring.php'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/bamboo-flooring-durable-hardwood.jpg" alt="strand woven bamboo floors" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/james.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Ambient
                                <span class="article-date">/ 05 Mar 2018</span>
                            </div>
                            <div class="article-title">Strand Woven Bamboo Floors: The Hardest...</div>
                            <p>Janka hardness tests show that solid strand bamboo floors are the hardest...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/the-pros-and-cons-of-bamboo-flooring'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/benefits-of-bamboo-flooring.jpg" alt="benefits of bamboo flooring" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/james.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                James S.
                                <span class="article-date">/ 14 Jul 2015</span>
                            </div>
                            <div class="article-title">Pros & Cons of Bamboo Flooring</div>
                            <p>Strand woven bamboo floors are many times harder than traditional...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('bamboo-floor-cleaning-care.php'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/cleaning-bamboo-flooring.jpg" alt="cleaning bamboo flooring" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img alt="avatar" data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/site-logo.png" class="lazyload">
                            </div>
                            <div class="article-author">
                                Ambient
                                <span class="article-date">/ 16 Oct 2018</span>
                            </div>
                            <div class="article-title">Bamboo Floor Care: Cleaning & Maintenance</div>
                            <p>Follow the instructions below for a long-lasting and beautiful floor...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/how-to-install-bamboo-flooring-over-concrete'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/install-bamboo-flooring-over-concrete.jpg" alt="can i install bamboo flooring on concrete" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/james.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                James S.
                                <span class="article-date">/ 02 July 2013</span>
                            </div>
                            <div class="article-title">Can I Install Bamboo Floors Over Concrete?</div>
                            <p> There are two basic methods for installing over concrete: the floating...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('bamboo-flooring-in-kitchens.php'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/kitchen-bamboo-flooring.jpg" alt="is bamboo flooring waterproof" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/site-logo.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Ambient
                                <span class="article-date">/ 23 Apr 2017</span>
                            </div>
                            <div class="article-title">Is Bamboo Flooring Good For Kitchens?</div>
                            <p>Some of the best materials for kitchen flooring include bamboo...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/bamboo-vs-luxury-vinyl-plank-lvp-lvt'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="https://www.ambientbp.com/blog/wp-content/uploads/2018/10/Bamboo-Flooring-VS-Luxury-Vinyl-Plank-979x102411.jpg" alt="bamboo vs lvp" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img alt="avatar" data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" class="lazyload">
                            </div>
                            <div class="article-author">
                                Gary S.
                                <span class="article-date">/ 16 Oct 2018</span>
                            </div>
                            <div class="article-title">Bamboo Hardwood VS Luxury Vinyl Plank</div>
                            <p>When choosing the right kind of flooring for your home, there’s a lot...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/bamboo-flooring-vs-engineered-hardwood'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/bamboo-vs-hardwood-flooring-comparison-chart.png" alt="bamboo vs hardwood flooring comparison chart" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/site-logo.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                James S.
                                <span class="article-date">/ 22 Oct 2013</span>
                            </div>
                            <div class="article-title">Bamboo VS Traditional Hardwood Flooring</div>
                            <p>When homeowners want the look of hardwood floors but prefer...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('carbonized-bamboo-flooring.php'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/carbonized-strand-hardwood-bamboo.jpg" alt="carbonized strand hardwood bamboo" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/site-logo.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Ambient
                                <span class="article-date">/ 05 mar 2018</span>
                            </div>
                            <div class="article-title">What is Carbonized Bamboo?</div>
                            <p>Bamboo is carbonized by subjecting it to high heat, which “caramelizes”...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/nail-glue-float-flooring-installation-best-method'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/glue-down-installation-bamboo-flooring.jpg" alt="nail vs glue vs float installation" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 15 May 2017</span>
                            </div>
                            <div class="article-title">Installation Methods: Nail VS Glue VS Float</div>
                            <p>There are different ways to install a beautiful bamboo floor, each has its...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/nail-down-bamboo-flooring'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/hammer.jpg" alt="hammer" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 27 Apr 2017</span>
                            </div>
                            <div class="article-title">How To Nail Down A Bamboo Floor</div>
                            <p>Thousands of people around the world nail down strand woven...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/gluing-down-a-bamboo-floor-a-guide'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/bamboo-flooring-gluing-installation.jpg" alt="how to glue down bamboo floors" class="lazyload" width="210" height="137">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/emma.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Emma M.
                                <span class="article-date">/ 11 May 2018</span>
                            </div>
                            <div class="article-title">How To Glue Down A Bamboo Floor</div>
                            <p>Before you start laying the bamboo planks, you have to do a little prep...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/how-to-install-a-floating-bamboo-floor'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/install-floating-bamboo-floor.jpg" alt="floating bamboo floor" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/emma.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Emma M.
                                <span class="article-date">/ 11 May 2018</span>
                            </div>
                            <div class="article-title">Installing Floating Bamboo Floors</div>
                            <p>There are three separate installation methods for bamboo flooring: glue...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/cost-of-installing-bamboo-flooring'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/bamboo-floor-installers.jpg" alt="cost of installation" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 23 Apr 2017</span>
                            </div>
                            <div class="article-title">How Much Does It Cost To Install Bamboo Floor?</div>
                            <p>There are three separate installation methods for bamboo flooring: glue...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/can-i-install-bamboo-flooring-in-a-basement'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/install-bamboo-flooring-in-a-basement.jpg" alt="can bamboo be installed in a basement" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/emma.jpg" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Emma M.
                                <span class="article-date">/ 05 Apr 2017</span>
                            </div>
                            <div class="article-title">Can I Install Bamboo Flooring In A Basement? </div>
                            <p>It’s good for rooms that are below ground because of its increased...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/is-bamboo-stronger-than-hardwood'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/bamboo-flooring-durable-hardwood.jpg" alt="is bamboo flooring more durable than hardwood" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 23 Apr 2017</span>
                            </div>
                            <div class="article-title">Is Bamboo Stronger than Hardwood?</div>
                            <p>Having a durable and resilient floor is a must if you have an active...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/extra-flooring-need-cutting-waste'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/extra-flooring.jpg" alt="how much extra do i order for cutting & waste?" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 23 Apr 2017</span>
                            </div>
                            <div class="article-title">How Much Extra Do I Order For Cutting?</div>
                            <p>Installers usually recommend that you purchase 10% extra...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo $helper_controller->site_url('blog/installing-bamboo-flooring-desert-tropical-climate-extremes'); ?>" class="article">
                        <div class="article-image-container">
                            <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/articles/desert-dry-bamboo-flooring-install.jpg" alt="does humidity affect bamboo floor" class="lazyload">
                            <div class="article-read-more-button">Read More</div>
                        </div>
                        <div class="article-description-container">
                            <div class="article-avatar">
                                <img data-src="<?php echo IMAGE_PATH; ?>bamboo-flooring-cost/no-image.png" alt="avatar" class="lazyload">
                            </div>
                            <div class="article-author">
                                Jared S.
                                <span class="article-date">/ 01 Dec 2017</span>
                            </div>
                            <div class="article-title">Install Bamboo Floors In A Dry or Humid Climate</div>
                            <p>Wood floors expand and contract naturally through the changing seasons...</p>
                            <div class="read-more">
                                Read More
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row text-center">
                <button class="btn articles-btn show-more-articles" type="button">View All Articles</button>
            </div>
        </div>
    </div>
</section>


    </div>
    <!-- footer -->
    <?php include("footer.php"); ?>
    <!-- product photo modal -->
    <div id="product-photo-modal" class="modal fade site-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img data-src="<?php echo IMAGE_PATH;?>assets/modal-close-icon.png" alt="modal close icon" width="49" height="53" data-dismiss="modal" class="lazyload">
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- product video modal -->
    <div id="product-video-modal" class="modal fade site-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img data-src="<?php echo IMAGE_PATH;?>assets/modal-close-icon.png" alt="modal close icon" width="49" height="53" data-dismiss="modal" class="lazyload">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="product-video-container"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- responsive filters button -->
    <button class="responsive-filter-button hidden-lg">Filters</button>
    <!-- responsive filters -->
    <div class="responsive-filters">
        <div class="responsive-filters-header">
            <h2>Filters</h2>
        </div>
        <div class="responsive-filters-content">
            <!-- responsive filters tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#responsive-sku-filter" role="tab" data-toggle="tab">Sku</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-species-filter" role="tab" data-toggle="tab">Flooring Type</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-surface-style-filter" role="tab" data-toggle="tab">Surface Style</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-features-filter" role="tab" data-toggle="tab">Features</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-wear-thickness-filter" role="tab" data-toggle="tab">Wear Layer Thickness</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-plank-length-filter" role="tab" data-toggle="tab">Plank Length</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-plank-width-filter" role="tab" data-toggle="tab">Plank Width</a>
                </li>
                <li role="presentation">
                    <a href="#responsive-plank-thickness-filter" role="tab" data-toggle="tab">Plank Thickness</a>
                </li>                
                <li role="presentation">
                    <a href="#responsive-color-group-filter" role="tab" data-toggle="tab">Color Group</a>
                </li>
            </ul>
            <!-- responsive filters tab panels -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="responsive-sku-filter">
                    <label id="responsiveFilter" for="responsive-sku-search" style="display: none;">Filter</label>
                    <input type="text" aria-labelledby="responsiveFilter" id="responsive-sku-search" name="sku_search" class="form-control" placeholder="Item Number">
                    <button class="btn btn-default" type="button">Go</button>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-species-filter">
                    <ul>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_type[]" value="bamboo">
                                    <span class="filter-name">Bamboo</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_type[]" value="eucalyptus">
                                    <span class="filter-name">Eucalyptus</span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-surface-style-filter">
                    <ul>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="handscraped">
                                    <span class="filter-name">Handscraped/Distressed</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="flat_smooth">
                                    <span class="filter-name">Flat/Smooth</span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-features-filter">
                    <ul>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="floating_tab">
                                    <span class="filter-name">Click-Lock (Floating)</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="tg_tab">
                                    <span class="filter-name">Nail or Glue Down</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="engineered-feature">
                                    <span class="filter-name">Engineered</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="waterproof">
                                    <span class="filter-name">Waterproof</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="bspc-rigid-core-bamboo">
                                    <span class="filter-name">SPC Rigid Core</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="basements">
                                    <span class="filter-name">For Basements</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="kitchen_friendly">
                                    <span class="filter-name">Kitchens &amp; Pet Friendly</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="floor_features[]" value="high_traffic">
                                    <span class="filter-name">High Traffic</span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-wear-thickness-filter">
                    <ul>
                        <?php
                        if(is_array($wear_thickness_array) && count($wear_thickness_array) > 0) {
                            foreach ($wear_thickness_array as $wear_thickness) {
                                if (!empty($wear_thickness)) {
                                    echo '<li>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="responsive_wear_thickness[]" value="'.$wear_thickness.'">
                                                    <span class="filter-name">'.$wear_thickness.'</span>
                                                </label>
                                            </div>
                                        </li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>                
                <div role="tabpanel" class="tab-pane" id="responsive-plank-length-filter">
                    <ul>
                        <?php
                                    if(is_array($plank_length_array) && count($plank_length_array) > 0) {
                                        foreach ($plank_length_array as $plank_length) {
                                            if (!empty($plank_length)) {
                                                ?>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="plank_length[]" value="<?php echo $plank_length;?>">
                                    <span class="filter-name"><?php echo $plank_length;?></span>
                                </label>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
                    ?>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-plank-width-filter">
                    <ul>
                        <?php
                                    if(is_array($plank_width_array) && count($plank_width_array) > 0) {
                                        foreach ($plank_width_array as $plank_width) {
                                            if (!empty($plank_width)) {
                                                ?>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="plank_width[]" value="<?php echo $plank_width;?>">
                                    <span class="filter-name"><?php echo $plank_width;?></span>
                                </label>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
                    ?>
                    </ul>
                </div>
                
                <div role="tabpanel" class="tab-pane" id="responsive-plank-thickness-filter">
                    <ul>
                        <?php
                                    if(is_array($plank_thickness_array) && count($plank_thickness_array) > 0) {
                                        foreach ($plank_thickness_array as $plank_thickness) {
                                            if (!empty($plank_thickness)) {
                                                ?>
                        <li>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="plank_thickness[]" value="<?php echo $plank_thickness;?>">
                                    <span class="filter-name"><?php echo $plank_thickness;?></span>
                                </label>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
                    ?>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="responsive-color-group-filter">
                    <ul>
                        <?php
                        if(is_array($color_group_array) && count($color_group_array) > 0) {
                            foreach ($color_group_array as $color_group) {  
                                if (!empty($color_group['color_group'])) {
                                    echo '<li>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="color_group[]" value="'.$color_group['color_group'].'">
                                                    <span class="filter-name">'.$color_group['color_group'].'</span>
                                                </label>
                                            </div>
                                        </li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="responsive-filters-footer">
            <button type="button" class="btn responsive-filters-cancel-button">Cancel</button>
            <button type="button" class="btn responsive-filters-apply-button">Apply</button>
        </div>
    </div>
    <div id="gallery-container"></div>
    <!-- scripts -->
    <?php if($agent == 'android' || $agent == 'iphone'){
        if(!empty($_GET['popup'])){
            echo $helper_controller->include_js('jquery/jquery-3.1.1.min.js,bootstrap/bootstrap.min.js,ambient.js');
            echo $helper_controller->include_js('alertify/alertify.min.js,flexslider/jquery.flexslider-min.js,bamboo-flooring-cost.js','plp-scripts');
        }else{
        echo $helper_controller->include_js('jquery/jquery-3.1.1.min.js,ambient.js');
        echo $helper_controller->include_js('bootstrap/bootstrap.min.js,alertify/alertify.min.js,flexslider/jquery.flexslider-min.js,bamboo-flooring-cost.js','plp-scripts');
        }
    }else{
        if(!empty($_GET['popup'])){
            echo $helper_controller->include_js('jquery/jquery-3.1.1.min.js,bootstrap/bootstrap.min.js,ambient.js,bamboo-flooring-cost.js');
            echo $helper_controller->include_js('flexslider/jquery.flexslider-min.js,alertify/alertify.min.js','plp-scripts');
    }else{
        echo $helper_controller->include_js('jquery/jquery-3.1.1.min.js,ambient.js,bamboo-flooring-cost.js');
        echo $helper_controller->include_js('bootstrap/bootstrap.min.js,flexslider/jquery.flexslider-min.js,alertify/alertify.min.js','plp-scripts');
        }
    }?>
	<script>
		var bootstrap_loaded = false;
		var script_loaded = false;
		var free_samples = '<?php echo (!empty($display_ambient_sample_section))? true : false ?>';
		var is_mobile = '<?php echo ($agent == 'android' || $agent == 'iphone')? true : false ?>';
		var image_path = '<?php echo IMAGE_PATH ?>';
		var system_path = '<?php echo SYSTEM_LIBRARY_PATH ?>';
        function lazyload_plp(){
			$('.news-letter-section').before($('<div>').load(base_url+'footer-info.php', { free_samples: free_samples, image_path : image_path, system_path : system_path, base_url : base_url }, function () {
					$(this).children().unwrap();
				}
			));

			setTimeout(function(){
				var src = $('#plp-styles').data('href');
				$('#plp-styles').attr('href',src);
				var src = $('#plp-scripts').data('src');
				$('#plp-scripts').attr('src',src);
			},2000);
		}
		$(document).ready(function() {
            var pos = 340;
            $("#top-section .goto-products-btn").click(function() {
                if($(window).width() <= 1200) {
                    pos = 190;
                }
                $('html, body').animate({
                    scrollTop: $("#products-listing").offset().top - pos
                }, 1000);
            });
			$(window).scrollTop(0);
			/*if(is_mobile){
				$('#plp-scripts').attr('onload','try{products_responsive("lazy");as_seen_trusted();filter_tooltip();}catch(e){}');
			}else{*/
				$('#plp-scripts').attr('onload','try{as_seen_trusted();filter_tooltip();}catch(e){}');
			/*}*/
			$("body").mousemove(function(){
			    if(script_loaded == false){
					script_loaded = true;
    			    lazyload_plp();
			    }
			});
			$(window).scroll(function(e){
				if(bootstrap_loaded == false){
					if(script_loaded == false){
						script_loaded = true;
					    lazyload_plp();
					}
					bootstrap_loaded = true;
				}
			});	
		});
		
		$(window).on("unload", function(){
			var scrollPosition = $(window).scrollTop();
			localStorage.setItem("scrollPosition_"+window.location.pathname, scrollPosition);
		});
	</script>
</body>
