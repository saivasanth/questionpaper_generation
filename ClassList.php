<?php
include_once 'Header.php';

$sql = "SELECT * FROM qpg_class_master ORDER BY id asc";
$classes = GetAll($sql);
?>
<script type="text/javascript">
classListInit();
</script>
<div class="qpgContent">
    <div class="qpgClassList">
        <table style="border:1px solid #cccccc;width:300px;" cellspacing="0" align="center">
            <tr style="background: #0099FF;">
                <td>
                    S.No
                </td>
                <td>
                    Class Name
                </td>
                <td>
                    Action
                </td>
            </tr>
        <?php
        foreach($classes as $class)
        {
        ?>
            <tr class="qpgClassRow" id="<?php echo $class['id'] ?>">
                <td>
                    <?php echo $class['id']; ?>
                </td>
                <td>
                    <?php echo $class['class_name']; ?>
                </td>
                <td>
                    View
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
    </div>
    <div class="qpgClassContents">
        <div id="qpgClassContainer">
            <br />
            <table style="width:200px;border-collapse: collapse;" align="center" border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        <span id="classIdContainer">
                            
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Class Name
                    </td>
                    <td>
                        <input type="text" id="classNameContainer" value="" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="button" value="Update" onclick="updateClassDetails()" />
                        <input type="button" value="Cancel" onclick="cancelClicked()" />
                        <input type="button" value="View All Subjects & Chapters" onclick="loadPage('ChapterList.php?classId=','1')" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>