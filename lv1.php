<!DOCTYPE html>
<html>

<head>
</head>

<body>
        <?php
        include('./simple_html_dom.php');
        interface iRadovi
        {
                public function create($data);
                public function save();
                public function read();
        }

        class DiplomskiRad implements iRadovi
        {
                private $_id = NULL;
                private $_naziv_rada = NULL;
                private $_tekst_rada = NULL;
                private $_link_rada = NULL;
                private $_oib_tvrtke = NULL;

                function __construct($data)
                {
                        $this->_id = uniqid();
                        $this->_naziv_rada = $data['naziv_rada'];
                        $this->_tekst_rada = $data['tekst_rada'];
                        $this->_link_rada = $data['link_rada'];
                        $this->_oib_tvrtke = $data['oib_tvrtke'];
                }

                function create($data)
                {
                        self::__construct($data);
                }

                function save()
                {
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "radovi";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                        }

                        $id = $this->_id;
                        $naziv = $this->_naziv_rada;
                        $tekst = $this->_tekst_rada;
                        $link = $this->_link_rada;
                        $oib = $this->_oib_tvrtke;

                        $sql = "INSERT INTO `diplomski_radovi` (`id`, `naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES ('$id', '$naziv', '$tekst', '$link', '$oib')";
                        if ($conn->query($sql) === true) {
                                $this->read();
                        } else {
                                echo "Error! " . $sql . "<br>" . $conn->error;
                        };
                        $conn->close();
                }

                function read()
                {
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "radovi";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM `diplomski_radovi`";
                        $output = $conn->query($sql);
                        if ($output->num_rows > 0) {
                                while ($item = $output->fetch_assoc()) {
                                        echo    "ID: " . $item["id"] .
                                                "<br>OIB tvrtke: " . $item["oib_tvrtke"] .
                                                "<br>Naziv rada: " . $item["naziv_rada"] .
                                                "<br>Link rada: " . $item["link_rada"] .
                                                "<br>Tekst rada: " . $item["tekst_rada"] .
                                                "<br><br><br>";
                                }
                        }
                        $conn->close();
                }
        }

        // //new db
        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        // $dbname = "radovi";
        // $conn = new mysqli($servername, $username, $password, $dbname);
        // if ($conn->connect_error) {
        //         die("Connection failed: " . $conn->connect_error);
        // }

        // //$sql = "CREATE DATABASE radovi";
        // $sql = "CREATE TABLE diplomski_radovi (
        //         id VARCHAR(13) PRIMARY KEY,
        //         naziv_rada VARCHAR(255),
        //         tekst_rada TEXT,
        //         link_rada VARCHAR(255),
        //         oib_tvrtke VARCHAR(11)
        // )";
        // if ($conn->query($sql) === TRUE) {
        //         echo "Database with name $dbname";
        // } else {
        //         echo "Error: " . $conn->error;
        // }
        // // Closing connection
        // $conn->close();

        $url = 'http://stup.ferit.hr/index.php/zavrsni-radovi/page/5';
        $fp  = fopen($url, 'r');
        $read = fgetcsv($fp);

        $read = file_get_html($url);
        foreach ($read->find('article') as $article) {

                foreach ($article->find('ul.slides img') as $img) {
                }
                foreach ($article->find('h2.entry-title a') as $link) {
                        $html = file_get_html($link->href);
                        foreach ($html->find('.post-content') as $text) {
                        }
                }
                $rad = array(
                        'naziv_rada' => $link->plaintext,
                        'tekst_rada' => $text->plaintext,
                        'link_rada' => $link->href,
                        'oib_tvrtke' => get_oib($img->src)
                );
                $newRad = new DiplomskiRad($rad);
                $newRad->save();
        }
        fclose($fp);

        function get_oib($src)
        {
                $totalLength = strlen($src);
                $oibLength = 11;
                $extensionLength = 4;
                $result = substr($src, $totalLength - ($extensionLength + $oibLength), $oibLength);
                return $result;
        }

        ?>
</body>

</html>