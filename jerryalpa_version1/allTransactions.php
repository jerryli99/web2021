<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b1a33"/>
    <link rel="icon" href="images/icon2.jpg">
    <link rel="apple-touch-icon" href="images/icon2.jpg">
    <link rel="stylesheet" href="stylePages/exchange.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Terrapin Exchange - ALL Transactions</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body{
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            border: 0;
            /* font-size: 80%; */
            /* font: inherit; */
            vertical-align: baseline;
            background-color: rgb(243, 246, 247);
            scroll-behavior: smooth;
        }

        h1{
            text-align: center;
            background-color: rgb(255, 213, 32);
            margin-top: 0;
            top: 50px;
            font-size: 200%;
            border: 5px solid black;
        }

        input[type=button]{
            background-color: black;
            color: white;
            padding: 8px 16px;
            font-size: 80%;
            border-radius: 15px;

            font-family: 'Courier New', Courier, monospace;
            display: inline-flex;
            flex-wrap: wrap;
            margin: 10px;
        }

        input[type=button]:hover{
            background-color: white;
            color: black;
        }

        div .goHome{
            background-color: rgb(255, 213, 32);
            padding: 8px 16px;
            font-size: 80%;
            border-radius: 15px;
            border: 3px solid black;
            text-decoration: none;

            font-family: 'Courier New', Courier, monospace;
            display: inline-flex;
            flex-wrap: wrap;
            margin: 10px;
        }

        #block{
            margin: 0 auto;
            width: 90%;
            height: fit-content;
            border: 1px solid black;
            padding: 0.5rem;
            word-break: break-all;
            background-color: white;
        }

        .arrow{
            text-align: center;
            font-size: 120%;
        }
    </style>
</head>
<body>
    <div>
        <h1>Transaction Records</h1>
        <form>
            <input type="button" name="save" value="Save record as PDF" onClick="window.print()">
            <br><a class="goHome" href="index">Go Back Home</a>
        </form>
	<p style="text-align:center">(It is better to save records on Computer)</p>
    </div>
</body>
</html>

<?php
session_start();
date_default_timezone_set('America/New_York');
require_once 'connectDB.php';

//there is a proof of work protocal (simplified)
class ProofOfWork{
    public static function getHash($data){
        return hash('sha256', $data);
    }

    //find the nonce
    public static function findNonce($data){
        $nonce = 0;
        while(!self::isValidNonce($data, $nonce)){
            ++$nonce;
        }
        return $nonce . 
        '<br><strong>Mined hash: </strong><br><mark>' . hash('sha256', $data . $nonce) . '</mark>' .
        '<br><strong>Valid nonce: </strong>' . self::isValidNonce($data, $nonce);
    }

    //We don't change the hash. 
    //We find the fist n zeros by adding data and nonce together, and then hash again
    //until we have the matching n zeros from index[0] to index[n] of hash($data); with
    //all of that, we can tell that the miner has been using his or her computer to calculate
    //the nonce value starting from 0 to 16^n possibilities(since sha256 is in hex format). 
    //Thus, proof of work is done.  
    public static function isValidNonce($data, $nonce){
        return 0 === strpos(hash('sha256', $data . $nonce), '0000');
    }
}

class PublicPrivateKey{
    public static function generateKeyPair(){
        //use RSA algorithm
        $res = openssl_pkey_new([
            "private_key_bits"=>2048,
            "private_key_type"=>OPENSSL_KEYTYPE_RSA
        ]);

        //get private key. The function has $privateKey as the output of private key.
        openssl_pkey_export($res, $privateKey);

        //return public key, private key.
        $publicKey = openssl_pkey_get_details($res)['key'];
        return [$publicKey, $privateKey];
    }

    public static function encrypt($sign, $privateKey){
        openssl_private_encrypt($sign, $crypted, $privateKey);
        return base64_encode($crypted);
    }

    public static function decrypt($crypted, $publicKey){
        openssl_public_decrypt(base64_decode($crypted), $decrypted, $publicKey);
        return $decrypted;
    }

    public static function isValidKey($sign, $crypted, $publicKey): bool{
        return $sign == self::decrypt($crypted, $publicKey);
    }
}

class Block{
    private $previousHash;
    private $hash;
    private $nonce;
    private $blockNumber;
    private $sign;
    private $publicKey;
    private $crypted;
    public $data;

    public function __construct($data, ?Block $previousHash){
        $this->previousHash = $previousHash ? $previousHash->hash : 'Genesis block';
        $this->data = $data;
        $this->mine();
    }

    public function mine(){
        $data = $this->data . $this->previousHash . $this->blockNumber;
        $this->nonce = ProofOfWork::findNonce($data);
        $this->hash = ProofOfWork::getHash($data . $this->nonce);
    }

    public function isVaild(): bool{
        return ProofOfWork::isValidNonce($this->data . $this->previousHash . $this->blockNumber, $this->nonce);
    }

    public function setSignPublicKey($publicKey){
        $this->publicKey = $publicKey;
    }

    public function getSignPublicKey(){
        return $this->publicKey;
    }

    public function signTransaction($sign){
        $this->sign = $sign;
        // [$publicKey, $privateKey] 
        $arrays = array();
        $arrays = PublicPrivateKey::generateKeyPair();
        // $this->setSignPublicKey($publicKey);
        $this->crypted = PublicPrivateKey::encrypt($this->sign, $arrays[1]);
        return $this->crypted;
    }

    public function setBlockNum($blockNum){
        return $this->blockNumber = $blockNum;
    }

    public function getBlockNum(){
        return $this->blockNumber;
    }

    public function getHash(){
        return $this->hash;
    }

    public function getPreviousHash(){
        return $this->previousHash;
    }

    public function __toString(){
        return '<div class="block" id="block"><strong>Block#: </strong>'  .  $this->blockNumber . 
               '<br><strong>PreviousHash: </strong><br><mark> ' . $this->previousHash . 
               '</mark><br><hr><strong>Nonce: </strong>' . $this->nonce . 
               '<br><br><strong>Transaction: <br></strong>' . $this->data . 
               '<br><strong>Hash: </strong><br><mark>' . $this->hash .
               '</mark><br><strong>Signed: </strong><br>' . $this->crypted  . 
               '<hr>Next Block</div><div class="arrow"><strong>
               <i class="fa fa-chain"></i></strong> chain&#8593;</div>';
    }

    //&#8593;... the chain.
}

class BlockChain{
    //put blocks in array
    public $blocks = [];

    public function __construct($data){
        $this->blocks[] = new Block($data, null);
        //set the genesis blockNumber to zero
        $this->blocks[0]->setBlockNum(0);
    }

    //add block to array blocks[], including the blockNumber
    public function add($data, $sign){
        $totalBlocks = count($this->blocks);
        $this->blocks[] = new Block($data, $this->blocks[$totalBlocks-1]);
        $this->blocks[$totalBlocks]->setBlockNum($totalBlocks);
        $this->blocks[$totalBlocks]->signTransaction($sign);
    }

    //check if it is a valid blockchain
    public function isValid(): bool{
        foreach($this->blocks as $i => $block){
            //checking two blocks' hash and previous hash
            if($i != 0 && $this->blocks[$i-1]->getHash() != $block->getPreviousHash()){
                echo '<h1 style="text-align:center">Not a valid chain. Check Block#'  .  
                     $this->blocks[$i-1]->getBlockNum() . ' hashes and the next block hashes.</h1><br>';
                return false;
            }
        }
        echo '<h1 style="text-align:center">Congradulations! This is a valid chain.</h1>';
        return true;
    }

    public function __toString(){
        // implode — Join array elements with a string
        return implode("\n\n" , $this->blocks);
    }
}

//put the transactions in the blocks, then chain them!!
$blockChain = new BlockChain('The first block -- nothing is in here &#128517;<br>', null);

$sql = "SELECT * FROM transactions";
$result = $connect->query($sql); 
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $blockChain->add($row['transaction'] . '<br><br><strong>Exchange time: </strong>' . 
        $row['transaction_time'] . '<br>', $row['signature'] . $row['transaction_time']);
    }
}
echo $blockChain . "\n<br><br><hr><center>Wonder World...</center><br><br>";
