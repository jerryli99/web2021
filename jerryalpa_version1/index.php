<?php
include_once 'header.php';
require_once 'productAPICall.php';
?>

    <main>
    <div class="small-container">
        <p id="message-to-terps" class="small-container-p"><strong><a style="text-decoration:underline;">"Terps &#128512;, exchange things you needdd!</a> You can exchange items and knowledge, or find a helper moneyless as long as you and the other person both agreed," Jerry said.</strong></p>
        <div class="separate"><hr></div>

        <ul class="categories"><span style="font-size: 90%;"><strong>Categories:</strong></span>
        <li class="list"><a href="classnotes.php" title="Exchange classnotes">Classnotes <i class="fa fa-pencil-square-o"></i></a></li>
        <li class="list"><a href="books.php" title="Exchange books">Books <i class="fa fa-book"></i></a></li>
        <li class="list"><a href="electronics.php" title="Exchange electronics">Electronics <i class="fa fa-desktop"></i></a></li>
        <li class="list"><a href="furnitures.php" title="Exchange furnitures">Furnitures <i class="fa fa-bed"></i></a></li>
        <li class="list"><a href="skills.php" title="Exchange skills">Skills <i class="fa fa-lightbulb-o"></i></a></li>
        <!-- <li class="list"><a href="business.php" title="Exchange">Business</a></li> -->
        </ul>
    </div>

    <div class="separate"><hr></div>
        <!-- This is the card section. Use php to add items in this style... I will delete this later -->
        <div class="content-wrap">
            <div class="row">

            <?php
                for($i = 0; $i < count($content); $i++){
                    echo '<div class="column">
                            <div class="card">' .
                                    '<img src="' . $content[$i][6] . '" alt=\"image missing\">' .
                                '<h3 id="userName">User:' . $content[$i][0] . '</h3>' . 
                                '<strong>' . $content[$i][1] . '</strong>' . '<p><strong></strong>' . 
                                date('Y-m-d H:i:s', $content[$i][5]) . '(click Exchange+ for more Info)</p>';
                             
                    if(isset($_SESSION['userName']) && isset($_SESSION['time']) && isset($_SESSION['valid'])){         
                            echo '<button onclick="window.location.href=\'exchangeInfo?itemNum=' . $i . '&' . 'productID=' . $content[$i][4] . '\'">Exchange +</button>';
                    }else{
                            echo '<button onclick="window.location.href=\'login\'">Exchange</button>';
                    }
                    echo '  </div>
                          </div>';
                }
            ?>       
            <!-- the /div below is the /div for class="row" -->
            </div> 
            <!-- end of content-wrap -->
        </div>
	<a id="Top" href="#"><strong><i class="fa fa-chevron-up"></i></strong></a>
    </main>
<?php
    include_once 'footer.php';
?>
 
