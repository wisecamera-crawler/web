<?php
/**
 * A controller for log related page
 *
 * PHP version 5
 *
 * LICENSE : none
 *
 * @category Controller
 * @package  Wisecamera
 * @author   Charlie Huang <huangckqq22@gmail.com>
 * @license  none <none>
 * @version  GIT: <git_id>
 * @link     none
 */
class Wisecamera_Export extends Wisecamera_CheckUser
{
    /**
     * Obtain user list
     *
     * This function is to get user list in DB
     * As a CI controller, the access path is :
     *      <baseurl>/index.php/log/getUsers
     * If it success, it will return list of user's id in DB
     *
     *
     * @author Charlie Huang <huangckqq22@gmail.com>
     * @version 1.0
     */
    public function exportAllDB()
    {
        $data="";

        $conn = mysql_connect("localhost", "root", "openfoundry");
        $db = mysql_select_db("NSC", $conn);

        $sql = "SELECT * FROM email";
        $rec = mysql_query($sql) or die (mysql_error());

        $num_fields = mysql_num_fields($rec);

        $header = "";
        for ($i = 0; $i < $num_fields; $i++) {
            $header .= mysql_field_name($rec, $i)."\\t";
        }

        while ($row = mysql_fetch_row($rec)) {
            $line = '';
            foreach ($row as $value) {
                if ((!isset($value)) || ($value == "")) {
                    $value = "\\t";
                } else {
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\\t";
                }
                $line .= $value;
            }
            $data .= trim($line) . "\\n";
        }

        //    $data = str_replace("\\r" , "" , $data);

        if ($data == "") {
            $data = "\\n No Record Found!\n";
        }

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=reports.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        print "$header\\n$data";

    }
}
