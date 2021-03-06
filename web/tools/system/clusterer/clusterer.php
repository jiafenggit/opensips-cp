<?php
/*
* Copyright (C) 2016 OpenSIPS Project
*
* This file is part of opensips-cp, a free Web Control Panel Application for
* OpenSIPS SIP server.
*
* opensips-cp is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* opensips-cp is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

require("template/header.php");
require("lib/".$page_id.".main.js");
require ("../../../common/mi_comm.php");
include("lib/db_connect.php");

$table=$config->table_clusterer;
$current_page="current_page_address";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;


#################
# start add new #
#################

if ($action=="add")
{
	extract($_POST);
	if(!$_SESSION['read_only'])
	{
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	}else {
		$errors= "User with Read-Only Rights";
	}

}

#################
# end add new   #
#################


####################
# start add verify #
####################
if ($action=="add_verify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$cln_cid=$_POST['cln_cid'];
		$cln_sid=$_POST['cln_sid'];
		$cln_url=$_POST['cln_url'];
		$cln_description=$_POST['cln_description'];

		$sql = "SELECT * FROM ".$table.
			" WHERE cluster_id=".$cln_cid." and machine_id=".$cln_sid;
		$resultset = $link->queryAll($sql);
		if(PEAR::isError($resultset)) {
			die('Failed to issue query, error message : ' . $resultset->getMessage());
		}

		if (count($resultset)>0) {
			$errors="Duplicate Cluster Node!";
		} else {
			$sql = "INSERT INTO ".$table." (cluster_id, machine_id, url, description) VALUES 
				(".$cln_cid.",".$cln_sid.",'".$cln_url."','".$cln_description."')";
			$resultset = $link->exec($sql);
			if(PEAR::isError($resultset)) {
                die('Failed to issue query, error message : ' . $resultset->getMessage());
            }
			$info="The new record was added";
		}
		$link->disconnect();
	}else{
		$errors= "User with Read-Only Rights";
	}

}
##################
# end add verify #
##################

#################
# start edit	#
#################
if ($action=="edit")
{

	if(!$_SESSION['read_only']){

		extract($_POST);

		require("template/".$page_id.".edit.php");
		require("template/footer.php");
		exit();
	}else{
		$errors= "User with Read-Only Rights";
	}
}
#############
# end edit	#
#############

#################
# start modify	#
#################
if ($action=="modify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$cle_id = $_GET['id'];
		$cle_cid=$_POST['cle_cid'];
		$cle_sid=$_POST['cle_sid'];
		$cle_url=$_POST['cle_url'];
		$cle_description=$_POST['cle_description'];

		if ( $cle_cid=="" || $cle_sid=="" || $cle_url=="" ){
			$errors = "Invalid data, the entry was not modified in the database";
		} else {
			$sql = "SELECT * FROM ".$table.
				" WHERE id!=".$cle_id." and cluster_id=".$cle_cid." and machine_id=".$cle_sid;
			$resultset = $link->queryAll($sql);
			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}

			if (count($resultset)>0) {
				$errors="Duplicate Cluster Node, database was not changed";
			} else {
				$sql = "UPDATE ".$table." set cluster_id=".$cle_cid.", machine_id=".$cle_sid.", url='".$cle_url."', description='".$cle_description."' where id=".$cle_id;
				$resultset = $link->exec($sql);
				if(PEAR::isError($resultset)) {
            	    die('Failed to issue query, error message : ' . $resultset->getMessage());
            	}
				$info="The cluster node was modified";
			}
			$link->disconnect();
		}
	}else{
		$errors= "User with Read-Only Rights";
	}
}
#################
# end modify	#
#################



################
# start delete #
################
if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id=$_GET['id'];

		$sql = "DELETE FROM ".$table." WHERE id=".$id;
		$link->exec($sql);
		$link->disconnect();
	}else{

		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############


################
# start search #
################
if ($action=="search")
{
	$_SESSION['cl_cid']=$_POST['cl_cid'];
	$_SESSION['cl_url']=$_POST['cl_url'];
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['cl_cid']="";
		$_SESSION['cl_url']="";
	} else if($search=="Search"){
		$_SESSION['cl_cid']=$_POST['cl_cid'];
		$_SESSION['cl_url']=$_POST['cl_url'];
	}
}
##############
# end search #
##############

##############
# start main #
##############

require("template/".$page_id.".main.php");
if($errors)
echo("<font color='red'><b>".$errors."</b></font>");
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
