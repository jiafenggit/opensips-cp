<?php
/*
 * $Id: index.php 133 2009-10-29 18:05:56Z iulia_bublea $
 * Copyright (C) 2011 OpenSIPS Project
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

######################################
# VERY IMPORTANT - THE MODULE ID     #
# must be identical with folder name #
######################################
 require("init.php");

 require("../../../common/cfg_comm.php");
 require("lib/functions.inc.php");
 session_start();
 get_priv("callcenter");
 header("Location: tviewer.php");
 
?>
