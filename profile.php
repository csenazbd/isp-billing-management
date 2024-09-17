<?php
   date_default_timezone_set("Asia/Dhaka");
   include("include/security_token.php");
   include("include/users_right.php");
   include "include/db_connect.php";
   
   
   
   if (isset($_GET['clid'])) {
   
       $clid = $_GET['clid'];
       if ($cstmr = $con->query("SELECT * FROM customers WHERE id='$clid'")) {
   
           while ($rows = $cstmr->fetch_array()) {
               $lstid = $rows["id"];
               $fullname = $rows["fullname"];
               $package = $rows["package"];
               $packagename = $rows["package_name"];
               $username = $rows["username"];
               $password = $rows["password"];
               $mobile = $rows["mobile"];
               $pop = $rows["pop"];
               $area = $rows["area"];
               $address = $rows["address"];
               $expiredDate = $rows["expiredate"];
               $createdate = $rows["createdate"];
               $profile_pic = $rows["profile_pic"];
               $nid = $rows["nid"];
               $price = $rows["price"];
               $remarks = $rows["remarks"];
           }
   
           $onlineusr = $con->query("SELECT * FROM radacct WHERE radacct.acctstoptime IS NULL AND username='$username'");
           $onlineusr->num_rows;
   
   
   
   
           // Disable Enable
           if (isset($_GET["disable"])) {
               if ($_GET["disable"] == 'true') {
                   $con->query("UPDATE customers SET status='0' WHERE id='$clid'");
                   $con->query("DELETE FROM radcheck WHERE username='$username'");
                   header("location:?clid=$clid");
               } else if ($_GET["disable"] == 'false') {
                   $con->query("UPDATE customers SET status='1' WHERE id='$clid'");
                   $con->query("INSERT INTO radcheck(username,value,attribute,op) VALUES('$username','$password','Cleartext-Password',':=')");
                   header("location:?clid=$clid");
               }
           }
       }
   }
   
   
   ?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>FAST-ISP-BILLING-SYSTEM</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
      <meta content="Themesbrand" name="author">
      <?php include 'style.php';?>
   </head>
   <body data-sidebar="dark">
      <!-- Begin page -->
      <div id="layout-wrapper">
         <?php
            $page_title="Profile"; 
            include 'Header.php'; 
            ?>
         <!-- ========== Left Sidebar Start ========== -->
         <div class="vertical-menu">
            <div data-simplebar class="h-100">
               <!--- Sidemenu -->
               <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">


                        <li>
                            <a href="index.php" class="waves-effect">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>
                        <li >
                            <?php
                            // if ($page_title=="Welcome To Dashboard") {
                            //    echo '<select name="menu_select_box" id="menu_select_box" class="form-select"></select>';
                            // }
                            
                            ?>
                            <select name="menu_select_box" id="menu_select_box" class="form-select"></select>
                        </li> 

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-account-check"></i>
                                <span>Customer </span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="customers.php">Total Customer List</a></li>
                                <li><a href="bulk_payment.php">Bulk Recharge</a></li>
                                <li><a href="active_cstmr_list.php">Active Customer List</a></li>
                                <li><a href="customer_expire.php">Expired Customer List</a></li>
                                <li><a href="customer_free_con.php">Free Customer List</a></li>
                                

                                <li>
                                    <a href="con_request.php">Connection Request 
                                        <?php 
                                            if ($allCstmr=$con->query("SELECT * FROM customers WHERE user_type='1' AND status=3")) {
                                                //echo $allCstmr->num_rows;
                                                if ($allCstmr->num_rows > 0) {
                                                    echo '<span class="badge rounded-pill bg-danger float-end">'.$allCstmr->num_rows.'<span>';
                                                }else{

                                                }
                                            }



                                                ?>
                                    </a>
                                </li>
                                <li><a href="customer_dues.php">Customer Dues/Unpaid</a></li>
                                <li><a href="customer_recharge.php">Customer Recharge</a></li>
                                <li><a href="credit_recharge_list.php">Credit Recharge List</a></li>
                                <li><a href="package_add.php">Customer Packages</a></li>
                                <li><a href="pwdmissmatch.php">Password missmatch</a></li>
                                <li><a href="customer_import.php">Import</a></li>
                                <!-- <li><a href="area.php">Area</a></li> -->
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-antenna"></i>
                                <span>Pop Managment</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="pop_branch.php">Pop Branch</a></li>
                                <li><a href="pop_area.php">Pop Area</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-account-plus-outline"></i>
                                <span>Customers Packages</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="pool.php">IP Pool</a></li>
                                <li><a href="package.php">Packages</a></li>
                                <li><a href="expired.php">Expired Date</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-ticket-outline"></i>
                                <span>Tickets</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="allTickets.php"> List All Tickets </a></li>
                                <li><a href="ticketsTopic.php"> Ticket Topics  </a></li>
                                <li><a href="working_group.php"> Working Group  </a></li>
                                <li><a href="works.php"> Works  </a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-book"></i>
                                <span>Accounts</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="ledger.php">Leger</a></li>
                                <li><a href="transactions.php">Transactions</a></li>
                                <li><a href="reports.php">Reports</a></li>
                                <li><a href="#">Settings</a></li>
                                <li><a href="cash_bank.php"> Cash/Bank List </a></li>
                                <li><a href="voucher.php">Voucher Entry</a></li>
                                <li><a href="bill_entry.php"> Bill Entry</a></li>
                                <li><a href="daybook.php">Daybook </a></li>
                                <li><a href="bank_maping.php">Bank Mapping  </a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class=" far fa-save"></i>
                                <span>Inventory</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="sale.php">Sale</a></li>
                                <li><a href="sales_invoice.php">Sale's Invoice</a></li>
                                <li><a href="purchase.php">Purchase</a></li>
                                <li><a href="purchase_invoice.php">Purchase Invoice</a></li>
                                <li><a href="product.php">Products</a></li>
                                <li><a href="category.php">Category</a></li>
                                <li><a href="brand.php">Brand</a></li>
                                <li><a href="client.php">Client </a></li>
                                <li><a href="store.php">Store</a></li>
                                <li><a href="supplier.php">Supplier</a></li>
                            </ul>
                        </li>
                        

                        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class= "fa fa-file"></i>
                                <span>Reports</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="customers_payment.php">Customer Payment</a></li>
                                <li><a href="recharge_filter.php">Recharge Filter</a></li>
                                <li><a href="bill_collection.php">Bill Collection</a></li>
                                <li><a href="cash_collection.php">Cash Collection</a></li>
                                <li><a href="mobile_banking.php">Mobile Banking Log</a></li>
                                <li><a href="bkash_search.php">bKash Search</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class= "far fa-envelope"></i>
                                <span>SMS</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="message_template.php">Message Templates</a></li>
                                <li><a href="send_message.php">Send SMS </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class= "fas fa-user-friends"></i>
                                <span>Users</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="users.php">User List</a></li>
                                <li><a href="users_log.php">Users Log</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class= "mdi mdi-power-settings menu-icon"></i>
                                <span>Settings</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="nas.php">NAS</a></li>
                                    <li><a href="router_setting.php">Router Settings</a></li>
                                <li><a href="password_change.php">Password Change</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
               <!-- Sidebar -->
            </div>
         </div>
         <!-- Left Sidebar End -->
         <!-- ============================================================== -->
         <!-- Start right Content here -->
         <!-- ============================================================== -->
         <div class="main-content">
            <div class="page-content">
               <div class="container-fluid">
                  <div class="row">
                     <div class="">
                        <div class="row">
                           <div class="col-md-6"></div>
                           <div class="col-md-6">
                              <div class="d-flex py-2" style="float:right;">
                                 <abbr title="Password Change">
                                 <button type="button" data-bs-target="#customerPasswordChangeModal" data-bs-toggle="modal" class="btn-sm btn btn-info">
                                 <i class="mdi mdi-key"></i>
                                 </button></abbr>
                                 &nbsp;
                                 <abbr title="Complain">
                                 <button type="button" data-bs-target="#ticketModal" data-bs-toggle="modal" class="btn-sm btn btn-warning ">
                                 <i class="mdi mdi-alert-outline"></i>
                                 </button></abbr>
                                 &nbsp;
                                 <abbr title="Recharge">
                                 <button type="button" id="rechargeBtn" class="btn-sm btn btn-primary ">
                                 <i class="mdi mdi mdi-battery-charging-90"></i>
                                 </button></abbr>
                                 &nbsp;
                                 <abbr title="Temp. Recharge">
                                 <button type="button" data-bs-target="#temp_recharge_Modal" data-bs-toggle="modal" class="btn-sm btn btn-secondary">
                                 <i class="mdi mdi mdi-battery-charging-20"></i>
                                 </button></abbr>
                                 &nbsp;
                                 <abbr title="Payment received">
                                 <button type="button" data-bs-target="#addPaymentModal" data-bs-toggle="modal" class="btn-sm btn btn-info ">
                                 <i class="mdi mdi mdi-cash-multiple"></i>
                                 </button></abbr>
                                 &nbsp;
                                 <?php
                                    if ($usrstatus = $con->query("SELECT * FROM radcheck WHERE username='$username' LIMIT 1")) {
                                    
                                        $radusrname = $usrstatus->num_rows;
                                    }
                                    if ($radusrname == 1) {
                                    
                                        echo '<abbr title="Disable"><a href="?clid=' . $clid . '&disable=true"><button type="button"
                                    class="btn-sm btn btn-danger">
                                    <i class="fas fa-user-slash"></i>
                                    </button></a></abbr>';
                                    } else {
                                    
                                        echo '<abbr title="Enable"> <a href="?clid=' . $clid . '&disable=false"><button type="button"
                                        class="btn-sm btn btn-success">
                                        <i class="fas fa-user-slash"></i>
                                        </button></a></abbr>';
                                    }
                                    ?>
                                 &nbsp;
                                 <abbr title="Reconnect">
                                 <button type="button" id="reconnect" class="btn-sm btn btn-pink">
                                 <i class="mdi mdi-sync"></i>
                                 </button>
                                 </abbr>
                                 &nbsp;
                                 <abbr title="Edit Customer">
                                 <a href="profile_edit.php?clid=<?php echo $clid; ?>">
                                 <button type="button" class="btn-sm btn btn-info">
                                 <i class="mdi mdi-account-edit"></i>
                                 </button></a>
                                 </abbr>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="container">
                        <div class="main-body">
                           <div class="row gutters-sm">
                              <div class="col-md-4 mb-3">
                                 <div class="card">
                                    <div class="card-body">
                                       <div class="d-flex flex-column align-items-center text-center profile">
                                          <img src="profileImages/avatar.png" class="rounded-circle" width="150" />
                                          <div class="mt-3">
                                             <h5>
                                                <?php echo $fullname; ?>
                                             </h5>
                                             <p class="text-secondary mb-1"># <?php echo $clid; ?>
                                                <br>
                                                <?php echo $mobile; ?>
                                             </p>
                                             <abbr title="User Since">
                                             <?php
                                                $createdate = new DateTime($createdate);
                                                $createdate = $createdate->format('d-M-Y');
                                                echo $createdate;
                                                ?>
                                             </abbr>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="card">
                                    <div class="card-body">
                                       <div class="col-12 bg-white p-0 px-2 pb-3 mb-3">
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="mdi mdi-marker-check"></i> Fullname:</p>
                                             <a href="#"><?php echo $fullname; ?></a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="mdi mdi-account-circle"></i> Username:</p>
                                             <a href="#"><?php echo $username; ?></a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class=" fas fa-dollar-sign"></i> Package:</p>
                                             <a href="#">
                                             <?php echo $packagename; ?>
                                             </a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="mdi mdi-phone"></i> Mobile:</p>
                                             <a href="#"><?php echo $mobile; ?></a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="mdi mdi-crosshairs-gps"></i>POP/Branch:</p>
                                             <a href="#">
                                             <?php
                                                $getPopId = $pop;
                                                if ($getData = $con->query("SELECT * FROM add_pop WHERE id='$getPopId' ")) {
                                                    while ($popName = $getData->fetch_array()) {
                                                        echo $popName['pop'];
                                                    }
                                                }
                                                
                                                
                                                ?>
                                             </a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="fas fa-location-arrow"></i> Area:</p>
                                             <a href="#">
                                             <?php $id = $area;
                                                $allArea = $con->query("SELECT * FROM area_list WHERE id='$id' ");
                                                while ($popRow = $allArea->fetch_array()) {
                                                    echo $popRow['name'];
                                                }
                                                
                                                ?>
                                             </a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="fas fa-id-card"></i> Nid No:</p>
                                             <a href="#"><?php echo $nid; ?></a>
                                          </div>
                                          <div class="d-flex justify-content-between border-bottom py-2 px-3">
                                             <p><i class="fas fa-id-card"></i>Remarks:</p>
                                             <a href="#"><?php echo $remarks; ?></a>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-8">
                                 <div class="row d-flex">
                                    <div class="col-md-12 mb-4">
                                       <div class="card  shadow py-2" style="border-left:3px solid green;">
                                          <div class="card-body">
                                             <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                   <?php
                                                      if ($onlineusr->num_rows == 1) {
                                                          echo '<span class="badge bg-success">Online</span>';
                                                      } else {
                                                          echo '<span class="badge bg-danger">Offline</span>';
                                                      }
                                                      
                                                      if ($DeviceMC = $con->query("SELECT callingstationid, framedipaddress FROM radacct WHERE username='$username' ORDER BY radacctid DESC LIMIT 1")) {
                                                          while ($mac_rows = $DeviceMC->fetch_array()) {
                                                              $DeviceMAC = $mac_rows["callingstationid"];
                                                              $framedipaddress = $mac_rows["framedipaddress"];
                                                          }
                                                          echo $DeviceMAC;
                                                          echo '</br>';
                                                          echo $framedipaddress;
                                                          echo '</br>';
                                                      
                                                          if (strlen($DeviceMAC) > 6) {
                                                      
                                                              $MACaddr = substr($DeviceMAC, 0, 8);
                                                              // Retrive MAC Data from database
                                                              if ($MC_vend = $con->query("SELECT vendor FROM mac_vendor WHERE mac='$MACaddr' LIMIT 1")) {
                                                                  while ($vend_rows = $MC_vend->fetch_array()) {
                                                                      $MAC_vendor = $vend_rows["vendor"];
                                                                      echo "<b>$MAC_vendor</b>";
                                                                  }
                                                              }
                                                          }
                                                      }
                                                      
                                                      
                                                      ?>
                                                </div>
                                                <div class="col">
                                                   <p>
                                                      <?php
                                                         if ($onlineusr->num_rows == 1) 
                                                         {
                                                             echo '<b><abbr title="Online"><img src="images/icon/online.png" height="10" width="10"/></abbr> Online </b> <br/>';
                                                             if ($lastuptime = $con->query("SELECT TIMEDIFF(NOW(), acctstarttime) AS time FROM radacct WHERE username='$username' AND acctstoptime IS NULL ORDER BY radacctid DESC LIMIT 1")) {
                                                                 $upt_rows = $lastuptime->fetch_array(); 
                                                                     $onlineHrs = $upt_rows["time"];
                                                         
                                                                    echo '<span class="far fa-clock"></span> <strong><span style="color:green;"> '.$onlineHrs. "</span></strong> Hrs <br/>"; 
                                                                 {
                                                                     
                                                                     
                                                                     if ($ontimes = $con->query("SELECT acctstarttime, acctinputoctets/1000/1000/1000 AS GB_IN, acctoutputoctets/1000/1000/1000 AS GB_OUT FROM radacct WHERE username='$username' ORDER BY radacctid DESC LIMIT 1")) {
                                                                         $on_rowss = $ontimes->fetch_array();
                                                                         $Download = $on_rowss["GB_OUT"];
                                                                         $Download = number_format($Download, 3);
                                                                         $Upload = $on_rowss["GB_IN"];
                                                                         $Upload = number_format($Upload, 3);
                                                         
                                                         
                                                                         //echo date("Y-m-d h:i:sa", strtotime($on_rowss["acctstarttime"]));
                                                                         echo '<span class="fas fa-caret-down" style="color:red;"> '.$Download.' GB</span><br/><span class="fas fa-caret-up" style="color:purple;"> '.$Upload.' GB</span><br/>';
                                                                         echo '<span class="fas fa-link text-green"></span><strong><span style="color:blue;"> '.date("h:i:s A", strtotime($on_rowss["acctstarttime"]))."</span></strong>";
                                                                         
                                                                     }
                                                                    /* */
                                                         
                                                                 }
                                                         
                                                                   
                                                             
                                                             }
                                                             
                                                         } else 
                                                         {
                                                             echo '<b><img src="images/icon/offline.png" height="10" width="10"/> Offline </b> <br/>';
                                                             if ($offtime = $con->query("SELECT TIMEDIFF(NOW(),acctstoptime) AS time FROM radacct WHERE username='$username' ORDER BY radacctid DESC LIMIT 1")) {
                                                                 $off_rows = $offtime->fetch_array();
                                                                 echo '<span class="far fa-clock"></span> <strong><span style="color:red;"> '.$offlineHours = $off_rows["time"]. "</span></strong> Hrs <br/>"; 
                                                                 {
                                                                     
                                                                     if ($offtimes = $con->query("SELECT acctstoptime FROM radacct WHERE username='$username' ORDER BY radacctid DESC LIMIT 1")) {
                                                                         $off_rowss = $offtimes->fetch_array();
                                                                         //echo date("Y-m-d h:i:sa", strtotime($off_rowss["acctstoptime"]));
                                                                         echo '<span class="fas fa-unlink text-green"></span><strong><span style="color:grey;"><abbr title='.date("Y-M-d h:i:s A", strtotime($off_rowss["acctstoptime"])).' >  '.date("h:i:s A", strtotime($off_rowss["acctstoptime"]))."</abbr></span></strong>";
                                                                     }
                                                         
                                                                 }
                                                                 
                                                             }
                                                            
                                                             
                                                         
                                                         
                                                         
                                                         }
                                                         
                                                         
                                                         
                                                         
                                                         
                                                         ?>
                                                   </p>
                                                </div>
                                                <div class="col">
                                                   <b>Monthly Uses</b>
                                                   <p>
                                                      <?php
                                                         $currentMonth = date("m");
                                                         if ($lastused = $con->query("SELECT SUM(acctinputoctets)/1000/1000/1000 AS GB_IN, SUM(acctoutputoctets)/1000/1000/1000 AS GB_OUT FROM
                                                         radacct WHERE username='$username' AND  MONTH(acctstarttime)='$currentMonth'")) {
                                                             $r_usd_rows = $lastused->fetch_array();
                                                             $Download = $r_usd_rows["GB_OUT"];
                                                             $Download = number_format($Download, 3);
                                                             $Upload = $r_usd_rows["GB_IN"];
                                                             $Upload = number_format($Upload, 3);
                                                         
                                                             echo '<span class="fas fa-caret-down" style="color:red;"> '.$Download.' GB</span><br/><span class="fas fa-caret-up" style="color:purple;"> '.$Upload.' GB</span><br/>';
                                                         
                                                         }
                                                         ?>
                                                   </p>
                                                </div>
                                                <div class="col">
                                                   <b>Expired Date</b><br>
                                                   <p><?php
                                                      if ($usrstatus = $con->query("SELECT * FROM radcheck WHERE username='$username' LIMIT 1")) {
                                                      
                                                          $radusrname = $usrstatus->num_rows;
                                                      }
                                                      
                                                      
                                                      if ($radusrname == 1) {
                                                          $expiredDate = new DateTime($expiredDate);
                                                          $expiredDate = $expiredDate->format('d-M-Y');
                                                          echo "<span style='color:green; solid;'>Active</span> <br>" . $expiredDate;
                                                      } else {
                                                      
                                                          echo '<a href="?clid=' . $clid . '&disable=false"><span style="color:red;">Disabled</span></a>';
                                                      }
                                                      echo '<br>';
                                                      $gracetime = $con-> query("SELECT DATEDIFF(grace_expired, NOW()) AS time FROM customers WHERE grace_expired>=NOW() AND username='$username'");
                                                      if($gracetime->num_rows ==1)
                                                      {
                                                      echo '<br><b><span style="color:red;">Grace Time</span></b><br>';
                                                      $grc_rows = $gracetime->fetch_array(); 
                                                      echo '<b>'.$grc_rows["time"].'</b> Days';
                                                      
                                                      
                                                      }
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      
                                                      ?></p>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <!-- Earnings (Monthly) Card Example -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                       <div class="card shadow py-2" style="border-left:3px solid #2A0FF1;">
                                          <div class="card-body">
                                             <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                   <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Recharged</div>
                                                   <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                      <?php
                                                         if ($rchdamt = $con->query("SELECT SUM(purchase_price) AS Amount FROM customer_rechrg WHERE customer_id='$clid' AND type !='4'")) {
                                                             while ($r_rchd_rows = $rchdamt->fetch_array()) {
                                                                 $totalrchd = $r_rchd_rows["Amount"];
                                                             }
                                                             echo $totalrchd;
                                                         }
                                                         ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Earnings (Monthly) Card Example -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                       <div class="card shadow  py-2" style="border-left:3px solid #27F10F;">
                                          <div class="card-body">
                                             <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                   <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Paid</div>
                                                   <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                      <?php
                                                         if ($dueamt = $con->query("SELECT SUM(purchase_price) AS Amount FROM customer_rechrg WHERE customer_id='$clid' AND type !='0'")) {
                                                             while ($r_due_rows = $dueamt->fetch_array()) {
                                                                 $totalpaid = $r_due_rows["Amount"];
                                                             }
                                                             echo $totalpaid;
                                                         }
                                                         ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Pending Requests Card Example -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                       <div class="card shadow  py-2" style="border-left:3px solid red;">
                                          <div class="card-body">
                                             <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                   <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Due</div>
                                                   <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                      <?php echo $totalDue = $totalrchd - $totalpaid; ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Pending Requests Card Example -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                       <div class="card shadow  py-2" style="border-left:3px solid blue;">
                                          <div class="card-body">
                                             <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                   <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Due paid</div>
                                                   <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                      <?php
                                                         if ($duepmt = $con->query("SELECT SUM(purchase_price) AS Amount FROM customer_rechrg WHERE customer_id='$clid' AND type='4'")) {
                                                             while ($pmt_rows = $duepmt->fetch_array()) {
                                                                 $totalpmtpaid = $pmt_rows["Amount"];
                                                             }
                                                             echo $totalpmtpaid;
                                                         }
                                                         ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="container">
                                    <div class="row">
                                       <div class="card">
                                          <div class="card-body">
                                             <!-- Nav tabs -->
                                             <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                                <li class="nav-item">
                                                   <a class="nav-link active" data-bs-toggle="tab" href="#usr_usage" role="tab">
                                                   <span class="d-none d-md-block">User Usage</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                                                   </a>
                                                </li>
                                                <li class="nav-item">
                                                   <a class="nav-link" data-bs-toggle="tab" href="#usr_activity" role="tab">
                                                   <span class="d-none d-md-block">User Activity</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                                                   </a>
                                                </li>
                                                <li class="nav-item">
                                                   <a class="nav-link" data-bs-toggle="tab" href="#tickets" role="tab">
                                                   <span class="d-none d-md-block">Tickets
                                                   </span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                                   </a>
                                                </li>
                                                <li class="nav-item">
                                                   <a class="nav-link" data-bs-toggle="tab" href="#cstmr_recharge" role="tab">
                                                   <span class="d-none d-md-block">Customers Recharge</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                                   </a>
                                                </li>
                                             </ul>
                                             <!-- Tab panes -->
                                             <div class="tab-content">
                                                <div class="tab-pane active p-3" id="usr_usage" role="tabpanel">
                                                   <div class="card">
                                                      <div class="card-body">
                                                         <div class="table-responsive">
                                                            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                               <thead>
                                                                  <tr>
                                                                     <th>Usage</th>
                                                                     <th>Duration</th>
                                                                     <th>Time</th>
                                                                     <th>Act.</th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                                                                  <?php
                                                                     if ($csusage = $con->query("SELECT acctinputoctets/1000/1000/1000 AS GB_IN, acctoutputoctets/1000/1000/1000 AS GB_OUT, TIMEDIFF(acctstoptime,acctstarttime) AS time, acctstoptime, acctterminatecause FROM
                                                                     radacct WHERE username='$username' AND acctstoptime IS NOT NULL ORDER BY acctstoptime DESC, radacctid ASC LIMIT 5")) 
                                                                     {
                                                                         while($r_ussg_rows = $csusage->fetch_array())
                                                                         {
                                                                     
                                                                         
                                                                         $usgDownload = $r_ussg_rows["GB_OUT"];
                                                                         $usgDownload = number_format($usgDownload, 3);
                                                                         $usgUpload = $r_ussg_rows["GB_IN"];
                                                                         $usgUpload = number_format($usgUpload, 3);
                                                                         $Usagetime = $r_ussg_rows["time"];
                                                                         $Usagestoptime = $r_ussg_rows["acctstoptime"]; 
                                                                         $Usagestoptime = date("h:i:s A ~ d-M-Y",strtotime($Usagestoptime)); 
                                                                         $Usageacctterminatecause = $r_ussg_rows["acctterminatecause"];                                                                                      
                                                                     
                                                                         //echo '<span class="fas fa-caret-down" style="color:red;"> '.$Download.' GB</span><br/><span class="fas fa-caret-up" style="color:purple;"> '.$Upload.' GB</span><br/>';
                                                                     
                                                                     
                                                                             echo '
                                                                             <tr>
                                                                             <td><span class="fas fa-caret-down"></span> '.$usgDownload.' GB<br/><span class="fas fa-caret-up" ></span> '.$usgUpload.' GB</td>
                                                                                 <td>'.$Usagetime.'</td>
                                                                                 <td>'.$Usagestoptime.'</td>
                                                                                 <td>'.$Usageacctterminatecause.'</td>
                                                                             </tr>
                                                                             ';
                                                                        }
                                                                     }
                                                                     
                                                                     
                                                                     ?>
                                                               </tbody>
                                                            </table>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="tab-pane p-3" id="usr_activity" role="tabpanel">
                                                   <div class="card">
                                                      <div class="card-body">
                                                         <div class="table-responsive">
                                                            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                               <thead>
                                                                  <tr>
                                                                     <th>Auth Date</th>
                                                                     <th>Username</th>
                                                                     <th>Pass</th>
                                                                     <th>Reply</th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                                                                  <?php
                                                                     if ($usrs_activity = $con->query("SELECT * FROM radpostauth WHERE username='$username' ORDER BY authdate DESC LIMIT 5")) {
                                                                         while ($rowsppp = $usrs_activity->fetch_array()) {
                                                                             $usr_act_name = $rowsppp["username"];
                                                                             $usr_act_pass = $rowsppp["pass"];
                                                                             $usr_act_reply = $rowsppp["reply"];
                                                                             $usr_act_auth = $rowsppp["authdate"];
                                                                             $usr_act_auth = date("h:i:s A ~ d-M-Y",strtotime($usr_act_auth)); 
                                                                     
                                                                             if ($usr_act_reply == "Access-Accept") {
                                                                                 $userReplay = "<span class='badge bg-success'>Password Matched</span>";
                                                                             } else if ($usr_act_reply == "Access-Reject") {
                                                                                 $userReplay = "<span class='badge bg-danger'>Password Missmatched</span>";
                                                                             }
                                                                     
                                                                             echo '
                                                                             <tr>
                                                                             <td>' . $usr_act_auth . '</td>
                                                                                 <td>' . $usr_act_name . '</td>
                                                                                 <td>' . $usr_act_pass . '</td>
                                                                                 <td>' .$userReplay. '</td>
                                                                             </tr>
                                                                             ';
                                                                         }
                                                                     }
                                                                     ?>
                                                               </tbody>
                                                            </table>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="tab-pane p-3" id="tickets" role="tabpanel">
                                                   <div class="card">
                                                      <div class="card-body">
                                                         <div class="table-responsive">
                                                            <table id="tickets_table" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                               <thead>
                                                                  <tr>
                                                                     <th>Complain Type</th>
                                                                     <th>Ticket Type</th>
                                                                     <th>Form Date</th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody id="ticket-list">
                                                                  <?php
                                                                     $sql = "SELECT * FROM ticket WHERE customer_id=$lstid  ";
                                                                     $result = mysqli_query($con, $sql);
                                                                     
                                                                     while ($rows = mysqli_fetch_assoc($result)) {
                                                                     
                                                                     ?>
                                                                  <tr>
                                                                     <td>
                                                                        <?php
                                                                           $complain_typeId = $rows["complain_type"];
                                                                           $ticketsId = $rows["id"];
                                                                           if ($allCom = $con->query("SELECT * FROM ticket_topic WHERE id='$complain_typeId' ")) {
                                                                               while ($rowss = $allCom->fetch_array()) {
                                                                                   $topicName = $rowss['topic_name'];
                                                                                   echo '<a href="tickets_edit.php?id=' . $ticketsId . '">' . $topicName . '</a>';
                                                                               }
                                                                           }
                                                                           ?>
                                                                     </td>
                                                                     <td>
                                                                        <?php
                                                                           $ticketType = $rows['ticket_type'];
                                                                           if ($ticketType == "Active") {
                                                                               echo "<span class='badge bg-success'>Active</span>";
                                                                           } else if ($ticketType == "Open") {
                                                                               echo "<span class='badge bg-info'>Open</span>";
                                                                           } else if ($ticketType == "New") {
                                                                               echo "<span class='badge bg-danger'>New</span>";
                                                                           } else if ($ticketType == "Complete") {
                                                                               echo "<span class='badge bg-success'>Complete</span>";
                                                                           }
                                                                           
                                                                           ?>
                                                                     </td>
                                                                     <td><?php echo $rows["startdate"]; ?></td>
                                                                  </tr>
                                                                  <?php } ?>
                                                               </tbody>
                                                            </table>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="tab-pane p-3" id="cstmr_recharge" role="tabpanel">
                                                   <div class="card">
                                                      <div class="card-body">
                                                         <div class="table-responsive">
                                                            <table id="recharge_data_table" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                               <thead>
                                                                  <tr>
                                                                     <th>Date</th>
                                                                     <th>Months</th>
                                                                     <th>Type</th>
                                                                     <th>Reference</th>
                                                                     <th>Paid until</th>
                                                                     <th>Amount</th>
                                                                     <th></th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody>
                                                                  <?php
                                                                     if ($recharge_customer = $con->query("SELECT * FROM customer_rechrg WHERE customer_id='$lstid' ")) {
                                                                         while ($r_cus_rows = $recharge_customer->fetch_array()) {
                                                                             $r_id = $r_cus_rows["id"];
                                                                             $r_cus_name = $r_cus_rows["customer_name"];
                                                                             $r_cus_month = $r_cus_rows["months"];
                                                                             $r_cus_ref = $r_cus_rows["ref"];
                                                                             $r_cus_amount = $r_cus_rows["purchase_price"];
                                                                     
                                                                             $r_unti = $r_cus_rows["rchrg_until"];
                                                                             $r_unti = new DateTime($r_unti);
                                                                             $r_unti = $r_unti->format('d-m-Y');
                                                                     
                                                                             $r_datetm = $r_cus_rows["datetm"];
                                                                             $r_datetm = new DateTime($r_datetm);
                                                                             //$r_datetm = $r_datetm->format('H:i A, d-M-Y');
                                                                             $r_datetm = $r_datetm->format('d-m-Y');
                                                                     
                                                                     
                                                                             $trnstype = $r_cus_rows['type'];
                                                                             if ($trnstype == "1") {
                                                                                 $trnstype = "<span class='badge bg-success'>Cash</span>";
                                                                             } else if ($trnstype == "2") {
                                                                                 $trnstype = "<span class='badge bg-info'>Bkash</span>";
                                                                             } else if ($trnstype == "3") {
                                                                                 $trnstype = "<span class='badge bg-success'>Nagat</span>";
                                                                             } else if ($trnstype == "4") {
                                                                                 $trnstype = "<span class='badge bg-primary'>Due Paid</span>";
                                                                             } else if ($trnstype == "0") {
                                                                                 $trnstype = "<span class='badge bg-danger'>Crdit</span>";
                                                                             }
                                                                     
                                                                     
                                                                             echo '
                                                                                     <tr>
                                                                                     <td>' . $r_datetm . '</td>
                                                                                         <td>' . $r_cus_month . '</td>
                                                                                         <td>' . $trnstype . '</td>
                                                                                         <td>' . $r_cus_ref . '</td>
                                                                                         <td>' . $r_unti . '</td>
                                                                                         <td>' . $r_cus_amount . '</td>
                                                                                         <td><button type="button" id="recharge_undo" data-id="'.$r_id.'" class="btn-sm btn btn-danger"><i class="mdi mdi-undo"></i></button></td>
                                                                                     </tr>
                                                                                     ';
                                                                         }
                                                                     }
                                                                     ?>
                                                               </tbody>
                                                            </table>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <!-- Modal for customer username and password update -->
            <div class="modal fade" id="customerPasswordChangeModal" tabindex="-1" role="dialog" aria-labelledby="Profile_pic_upload_Label" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="Profile_pic_upload_Label">Update Customer Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form>
                           <div class="from-group d-none">
                              <label>Customer id:</label>
                              <input class="" type="text" id="update_customer_id" value="<?php echo $lstid; ?>">
                           </div>
                           <div class="from-group mb-2">
                              <label>Customer Username:</label>
                              <input type="text" id="update_customer_username" class="form-control " placeholder="Enter Customer Username" value="<?php echo $username; ?>">
                           </div>
                           <div class="from-group mb-2">
                              <label>Customer Password:</label>
                              <input type="text" id="update_customer_password" class="form-control " placeholder="Enter Customer Password" value="<?php echo $password; ?>">
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" id="customer_update_btn" class="btn btn-primary">Update Now</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Modal for Ticket -->
            <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header  bg-success">
                            <h5 class="modal-title text-white " id="exampleModalLabel">Ticket Add&nbsp;&nbsp;<i class="mdi mdi-account-plus"></i></h5>
                            
                        </div>
                        <form action="include/tickets_server.php?add_ticket_data=true" method="POST" id="ticket_modal_form">
                            <div class="modal-body">
                                <div class="from-group mb-2">
                                    <label>Customer Name</label>
                                    <select class="form-select" name="customer_id" id="ticket_customer_id" style="width: 100%;">
                                        <option value="<?php echo $lstid; ?>"><?php echo $username;?></option>
                                    </select>
                                </div>
                                <div class="from-group mb-2">
                                    <label for="">Ticket For</label>
                                    <select id="ticket_for" name="ticket_for" class="form-select" required>
                                        <option value="Home Connection">Home Connection</option>
                                        <option value="POP">POP Support</option>
                                        <option value="Corporate">Corporate</option>
                                        
                                    </select>
                                </div>
                                <div class="from-group mb-2">
                                    <label for=""> Complain Type </label>
                                    <select id="ticket_complain_type" name="ticket_complain_type" class="form-select" style="width: 100%;" ></select>

                                </div>
                                <div class="from-group mb-2">
                                    <label for="">Ticket Priority</label>
                                    <select id="ticket_priority" name="ticket_priority" type="text" class="form-select" style="width: 100%;">
                                    <option >---Select---</option>
                                    <option value="1">Low</option>
                                    <option value="2">Normal</option>
                                    <option value="3">Standard</option>
                                    <option value="4">Medium</option>
                                    <option value="5">High</option>
                                    <option value="6">Very High</option>
                                    </select>
                                </div>
                                <div class="from-group mb-2">
                                    <label for="">Assigned To</label>
                                    <select id="ticket_assigned" name="assigned" class="form-select" style="width: 100%;"></select>
                                </div>
                                <div class="from-group mb-2">
                                    <label for="">Note</label>
                                    <input id="notes" type="text" name="notes" class="form-control" placeholder="Enter Your Note">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal for Recharge -->
            <div class="modal fade" id="rechargeModal" tabindex="-1" role="dialog" aria-labelledby="ComplainModalCenterTitle" aria-hidden="true" >
               <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="ComplainModalLongTitle">
                           Recharge [<span style="color:red;" id="currentBal">Due Balance: <?php echo $totalDue; ?> </span>]
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form id="recharge-form" method="POST">
                           <div class="form-group d-none">
                              <label for="">id</label>
                              <input id="customer_id" type="text" value="<?php echo $clid; ?>" class="form-control form-control-sm">
                              <input id="pop_id" type="text" value="<?php echo $pop; ?>" class="form-control form-control-sm">
                           </div>
                           <div id="holders">
                              <div class="form-group mb-1">
                                 <label>Month</label>
                                 <select id="month" class="form-select" name='month'>
                                    <option value="">Select</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                 </select>
                              </div>
                              <div class="form-group mb-1">
                                 <label for="">Package</label>
                                 <input id="package" disabled="Disable" name="package" class="form-control " value="<?php echo $packagename; ?>" />
                              </div>
                              <div class="form-group mb-1 ">
                                 <label>Package Price:</label>
                                 <input id="amount" disabled="Disable" type="text" class="form-control form-control-sm" value="<?php echo $price; ?>">
                              </div>
                           </div>
                           <div class="form-group mb-1 ">
                              <label>Payable Amount:</label>
                              <input id="MainAmount"  type="text" class="form-control form-control-sm" />
                           </div>
                           <div class="form-group mb-1 ">
                              <label>Ref No.:</label>
                              <input id="RefNo" type="text" class="form-control form-control-sm" />
                           </div>
                           <div class="form-group mb-1">
                              <label>Transaction Type:</label>
                              <select id="tra_type" name="tra_type" class="form-select">
                                 <option value="1">Cash</option>
                                 <option value="0">On Credit</option>
                                 <option value="2">Bkash</option>
                                 <option value="3">Nagad</option>
                                 <option value="4">Due Payment</option>
                              </select>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button id="recharge-button" type="button" class="btn btn-success">Recharge Now</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-----------------temp Customer Recharge------------------->
            <div class="modal fade" id="temp_recharge_Modal" tabindex="-1" role="dialog" aria-labelledby="ComplainModalCenterTitle" aria-hidden="true" >
               <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="ComplainModalLongTitle">
                           Recharge [<span style="color:red;" id="currentBal">Due Balance: <?php echo $totalDue; ?> </span>]
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form id="recharge-form" method="POST">
                           <div class="form-group d-none">
                              <label for="">id</label>
                              <input id="customer_id" type="text" value="<?php echo $clid; ?>" class="form-control form-control-sm">
                              <input id="pop_id" type="text" value="<?php echo $pop; ?>" class="form-control form-control-sm">
                           </div>
                           <div class="form-group mb-1">
                              <label>Days</label>
                              <select id="days" class="form-select">
                                 <option value="">Select</option>
                                 <option value="01">1</option>
                                 <option value="02">2</option>
                                 <option value="03">3</option>
                                 <option value="4">4</option>
                                 <option value="5">5</option>
                                 <option value="6">6</option>
                                 <option value="7">7</option>
                                 <option value="8">8</option>
                                 <option value="9">9</option>
                                 <option value="10">10</option>
                                 <option value="11">11</option>
                                 <option value="12">12</option>
                              </select>
                           </div>
                           <div class="form-group mb-1 ">
                              <label>Ref No.:</label>
                              <input id="RefNo" type="text" class="form-control form-control-sm" />
                           </div>
                           <div class="form-group mb-1">
                              <label>Transaction Type:</label>
                              <select id="tra_type" name="tra_type" class="form-select">
                                 <option value="0">On Credit</option>
                              </select>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button id="temp_recharge" type="button" class="btn btn-success"><i class="mdi mdi-cash"></i> Add Recharge</button>
                     </div>
                  </div>
               </div>
            </div>
            <!--------------------Add Payment received Modal---------------------------->
            <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="addPaymentModal">
               <div class="modal-dialog" role="document">
                  <form id="FormData">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLabel">Add Payment Received</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="card">
                           <div class="card-body">
                              <input class="d-none" type="text" id="due_recharge_pop_id" value="<?php echo $pop; ?>">
                              <input id="due_customer_id" type="text" value="<?php echo $clid; ?>" class="form-control d-none">
                              <div class="form-group mb-2">
                                 <label>Amount:</label>
                                 <input type="text" id="addRechargeAmount" placeholder="Enter Your Amount" class="form-control">
                              </div>
                              <div class="form-group mb-2">
                                 <label>Transaction Type:</label>
                                 <select id="addRechargeTra_type" class="form-select">
                                    <option value="1">Cash</option>
                                    <option value="2">Bkash</option>
                                    <option value="3">Nagad</option>
                                    <option value="4">Bank</option>
                                 </select>
                              </div>
                              <div class="form-group mb-2">
                                 <label>Remarks</label>
                                 <textarea id="addRechargeRemarks" class="form-control" placeholder="Enter Remarks"></textarea>
                              </div>
                              <div class="form-group d-none">
                                 <label>Recharge By:</label>
                                 <input type="text" id="recharge_by" class="form-control" value="<?php echo $_SESSION['uid']; ?>" />
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-danger">Cancle</button>
                           <button type="button" id="addPaymentBtn" class="btn btn-primary"><i class="mdi mdi-cash"></i> Add Payment</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <?php include 'Footer.php';?>
         </div>
         <!-- end main content-->
      </div>
      <!-- END layout-wrapper -->
      <!-- Right bar overlay-->
      <div class="rightbar-overlay"></div>
      <?php include 'script.php';?>
       <!-- Include Tickets js File -->
    <!-- <script src="js/tickets.js"></script> -->
      <script type="text/javascript">
         $('#tickets_table').dataTable();
         $('#recharge_data_table').dataTable();
         $('#user_activity_data_table').dataTable();
         showModal();
         
         function showModal() {
             $("#rechargeBtn").click(function() {
                 $("#rechargeModal").modal('show');
                 $("#month").on('click', function() {
                     var month = $("#month").val();
                     var amount = $("#amount").val();
                     totalAmount = (month * amount);
                     $("#MainAmount").val(totalAmount);
                 });
             });
         }
         //addRecharge();
         
         
        $("#recharge-button").click(function() {
                 var customer_id = $("#customer_id").val();
                 var month = $("#month").val();
                 var package = $("#package").val();
                 var mainAmount = $("#MainAmount").val();
                 var RefNo = $("#RefNo").val();
                 var tra_type = $("#tra_type").val();
                 var pop_id = $("#pop_id").val();

                var RechargData =
                    "customer_id=" + customer_id+
                    "&month=" + month+
                    "&package=" + package+
                    "&amount=" + mainAmount+
                    "&RefNo=" + RefNo+
                    "&tra_type=" + tra_type+
                    "&pop_id=" + pop_id+
                    "&add_recharge_data=0";
                if (month.length == 0 && $("#tra_type").val() != 4) {
                    toastr.error("Select Month");
                } else if (tra_type.length = "") {
                    toastr.error("Select Transaction");
                } else {
                    $("#recharge-button").disabled;
                    $.ajax({
                        type: 'POST',
                        url: 'include/customer_recharge_server.php',
                        data: RechargData,
                        success: function(response) {
                        // alert(response);
                            if (response == 1) {
                                toastr.success("Recharge Successful");
                                $("#rechargeModal").modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else if(response == 2) {
                                toastr.error("Please Recharge This POP Account");
                            }
            
                        }
                    });
                }
         
         });
         
         //add due payment processing 
         $(document).on('click', '#addPaymentBtn', function() {
             var pop_id = $("#due_recharge_pop_id").val();
             var recharge_amount = $("#addRechargeAmount").val();
             var due_customer_id = $("#due_customer_id").val();
             var transaction_type = $("#addRechargeTra_type").val();
             var remarks = $("#addRechargeRemarks").val();
             
         
         
             if (recharge_amount.length == 0) {
                 toastr.error("Please Enter Amount");
             } else {
                 $.ajax({
                     type: 'POST',
                     url: 'include/customer_recharge_server.php',
                     data: {
                         customer_id: due_customer_id,
                         amount: recharge_amount,
                         remarks: remarks,
                         transaction_type: 4,
                         pop_id: pop_id,
                         addCustomerDuePayment: 0
                     },
                     success: function(response) {
                         if (response == 1) {
                             
                             toastr.success("Payment Success");
                             $("#addPaymentModal").modal('hide');
                             setTimeout(() => {
                                 location.reload();
                             }, 1000);
                         } else {
                             toastr.error(response);
                         }
                     }
                 });
             }
         });
         
         
         $(document).on('click', '#temp_recharge', function() {
             var pop_id = $("#due_recharge_pop_id").val();
             var due_customer_id = $("#due_customer_id").val();
             var days = $("#days").val();
         
             var RefNo = $("#RefNo").val();
             var tra_type = $("#tra_type").val();
         
         
             if (days.length == 0) {
                 toastr.error("Please Select Your Days");
             } else {
                 $.ajax({
                     type: 'POST',
                     url: 'include/customer_recharge_server.php',
                     data: {
                         customer_id: due_customer_id,
                         pop_id: pop_id,
                         days: days,
                         RefNo: RefNo,
                         transaction_type: tra_type,
                         customer_temp_recharge: 0
                     },
                     success: function(response) {
         
                         if (response == 1) {
                             toastr.success("Recharge Success");
                             $("#temp_recharge_Modal").modal('hide');
                             setTimeout(() => {
                                 location.reload();
                             }, 1000);
                         } else {
                             toastr.error(response);
                         }
         
                     }
                 });
             }
         });
         //customer username and password update script
         $("#customer_update_btn").click(function() {
             var customerId = $("#update_customer_id").val();
             var customerUsername = $("#update_customer_username").val();
             var customerPassword = $("#update_customer_password").val();
         
             if (customerUsername.length == 0) {
                 toastr.error("Please Enter Your Username");
             } else if (customerUsername.length == 0) {
                 toastr.error("Please Enter Your Password");
             } else {
                 // সার্ভারে ডাটা পাঠানো
                 $.ajax({
                     url: "include/customers_server.php",
                     method: "POST",
                     data: {
                         customer_id: customerId,
                         customer_username: customerUsername,
                         customer_password: customerPassword,
                         updateCustomerData: 0
                     },
                     success: function(response) {
                         if (response == 1) {
                             toastr.success("Update Success");
                             $("#customerPasswordChangeModal").modal('hide');
                             setTimeout(() => {
                                 location.reload();
                             }, 1000);
                         } else {
                             toastr.error(response);
                         }
                     },
                     error: function(error) {
                         console.log(error);
                     }
                 });
             }
         
         });
         
         //customer username and password update script
         $("#reconnect").click(function() {
             //var customerId = $("#update_customer_id").val();
             //var customerFramedIP = <?php echo $framedipaddress; ?>;
             
             alert("customerFramedIP");
         
             
         
         });
         
         
         
         
        
         
         
         $(document).on('click', '#recharge_undo', function() {
         
         var rechargeID = $(this).attr("data-id");
         
         var confrm = confirm("Are you sure undo recharge ?"+rechargeID);
         
         
         if(confrm)
         {
         
         $.ajax({
             type: 'POST',
             url: 'include/customer_recharge_server.php',
             data: {
                 rechargeID: rechargeID,
                 undo_customer_recharge: 0
             },
             success: function(response) {
         
                 if (response == 1) 
                 {
                     toastr.success("Recharge Undone!");
                     setTimeout(() => {
                                 location.reload();
                             }, 1000);
                 } 
                 else 
                 {
                     toastr.error(response);
                 }
         
             }
         });
         
         }
         
         });


         /*** Add Modal Script****/
        $('#ticketModal').on('show.bs.modal', function (event) {
             loadCustomers();
             ticket_assign();
             ticket_complain_type(); 
            /*Check if select2 is already initialized*/ 
            if (!$('#ticket_customer_id').hasClass("select2-hidden-accessible")) {
                $("#ticket_customer_id").select2({
                    dropdownParent: $('#ticketModal'),
                    placeholder: "Select Customer"
                });
                $("#ticket_assigned").select2({
                    dropdownParent: $('#ticketModal'),
                    placeholder: "---Select---"
                });
                $("#ticket_complain_type").select2({
                    dropdownParent: $('#ticketModal'),
                    placeholder: "---Select---"
                });
                $("#ticket_priority").select2({
                    dropdownParent: $('#ticketModal'),
                    placeholder: "---Select---"
                });
            }
        });
        function loadCustomers() {
            $.ajax({
                url: 'include/tickets_server.php?get_all_customer=true',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success==true) {
                        let customerSelect = $("#ticket_customer_id");
                        customerSelect.empty(); 
                        customerSelect.append('<option value="">---Select Customer---</option>');
                        $.each(response.data, function (index, customer) {
                            customerSelect.append('<option value="' + customer.id + '">[' + customer.id + '] - ' + customer.username + ' || ' + customer.fullname + ', (' + customer.mobile + ')</option>');
                        });
                    }
                    $("#ticket_customer_id").val(<?php echo $lstid;?>);
                }
            });
        }
        
        function ticket_assign(){
            /* Make AJAX request to server*/
            $.ajax({
                url: "include/tickets_server.php", 
                type: "POST",
                data: {
                    customer_id: <?php echo $lstid; ?>,
                    get_area:true,
                },
                success: function(response) {
                    /* Handle the response from the server*/
                    $("#ticket_assigned").html(response);
                }
            });
        }
        function ticket_complain_type(){
            /* Make AJAX request to server*/
            $.ajax({
                url: "include/tickets_server.php", 
                type: "POST",
                data: {
                    get_complain_type:true,
                },
                dataType:'json',
                success: function(response) {
                    /* Handle the response from the server*/
                    //$("#ticket_complain_type").html(response);
                    if (response.success==true) {
                        let ticket_complain_type = $("#ticket_complain_type");
                        ticket_complain_type.empty(); 
                        ticket_complain_type.append('<option value="">---Select---</option>');
                        $.each(response.data, function (index, item) {
                            ticket_complain_type.append('<option value="' + item.id + '">'+item.topic_name+'</option>');
                        });
                    }
                }
            });
        }
        $("#ticket_modal_form").submit(function(e) {
            e.preventDefault();

            /* Get the submit button */
            var submitBtn = $(this).find('button[type="submit"]');
            var originalBtnText = submitBtn.html();

            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden"></span>');
            submitBtn.prop('disabled', true);

            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType:'json',
                success: function(response) {
                    if (response.success) {
                        $("#ticketModal").fadeOut(500, function() {
                            $(this).modal('hide');
                            toastr.success(response.message);
                            //$('#tickets_datatable').DataTable().ajax.reload();
                        });

                    } else if (!response.success && response.errors) {
                        $.each(response.errors, function(field, message) {
                            toastr.error(message);
                        });
                    }
                },
                complete: function() {
                    submitBtn.html(originalBtnText);
                    submitBtn.prop('disabled', false);
                }
            });
        });
         
      </script>
     
       
   </body>
</html>