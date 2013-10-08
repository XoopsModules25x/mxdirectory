<?php
// $Id: brokenlink.php,v 1.8 2003/03/27 12:11:06 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //
if( ! class_exists( 'Coupon' ) ) {
$mydirname = basename (dirname ( dirname( __FILE__ ) ) );

class Coupon extends XoopsObject {
    //Constructor
    /** 
    * @param mixed $coupid int for coupon id or array with name->value pairs of properties
    * @return object {@link Coupon}
    */
	function Coupon($coupid = false) {
		global $mydirname;
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('couponid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, true);
		$this->initVar('description', XOBJ_DTYPE_TXTAREA);
		$this->initVar('image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('publish', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('expire', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('heading', XOBJ_DTYPE_TXTBOX);
		$this->initVar('counter', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('lbr', XOBJ_DTYPE_INT, 0, false);
		if ($coupid != false) {
            global $mydirname;
			if (is_array($coupid)) {
                $this->assignVars($coupid);
      } else {
				global $mydirname;
				$coupon_handler = xoops_getmodulehandler('coupon', $mydirname);
				$coupon =& $coupon_handler->get($coupid);
				foreach ($coupon->vars as $k => $v) {
				  $this->assignVar($k, $v['value']);
				}
				unset($coupon);
      }
    }
  }

  function toArray() {
    $ret = array();
    foreach ($this->vars as $k => $v) {
      $ret[$k] = $v['value'];
    }
    return $ret;
  }
}
}
// Change the class name below to enable custom directory (Capitolize first letter YourdirectoryCouponHandler)
class XdirectoryCouponHandler extends XoopsObjectHandler {
    /**
     * create a new coupon object
     * 
     * @param bool $isNew flag the new objects as "new"?
     * @return object {@link Coupon}
     */
    function &create($isNew = true)
    {
        $coupon = new Coupon();
        if ($isNew) {
            $coupon->setNew();
        }
        return $coupon;
    } 
    /**
     * retrieve a coupon
     * 
     * @param int $coupid ID of the coupon
     * @return mixed reference to the {@link Coupon} object, FALSE if failed
     */
    function &get($coupid = false) {
        if ($coupid == false) {
            return false;
        }
        $coupid = intval($coupid);
        if ($coupid > 0) {
            $sql = "SELECT * FROM ".$this->db->prefix("xdir_coupon")." WHERE couponid=".$coupid;
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $coupon =& $this->create(false);
            $coupon->assignVars($this->db->fetchArray($result));
            return $coupon;
        }
        return false;
    }
    
    /**
    * Save coupon in database
    * @param object $coupon reference to the {@link Coupon} object
    * @param bool $force 
    * @return bool FALSE if failed, TRUE if already present and unchanged or successful
    */
    function insert(&$coupon) {
        if (get_class($coupon) != 'Coupon') { // EVU CODE FIX - To make it work using PHP5
            return false;
        }
        if (!$coupon->isDirty()) {
            return true;
        }
        if (!$coupon->cleanVars()) {
            return false;
        }
        foreach ($coupon->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($coupon->_isNew) {
            $sql = "INSERT INTO ".$this->db->prefix("xdir_coupon")." 
                    (lid, description, image, publish, expire, heading, lbr) VALUES 
                    ($lid, ".$this->db->quoteString($description).", ".$this->db->quoteString($image).", $publish, $expire, ".$this->db->quoteString($heading).", $lbr)";
        }
        else {
            $sql = "UPDATE ".$this->db->prefix("xdir_coupon")." SET
                    lid = $lid,
                    description = ".$this->db->quoteString($description).",
					image = ".$this->db->quoteString($image).",
					publish = $publish,
                    lbr = $lbr,
                    heading = ".$this->db->quoteString($heading).",
                    expire = $expire WHERE couponid = ".$couponid;
        }
        if (!$this->db->query($sql)) {
            return false;
        }
        if ($coupon->_isNew) {
            $coupon->setVar('couponid', $this->db->getInsertId());
            $coupon->_isNew = false;
        }
        return true;
    }
    
    /**
    * delete a coupon from the database
    *
    * @param object $coupon reference to the {@link Coupon} to delete
    * @param bool $force
    * @return bool FALSE if failed.
    */
    function delete(&$coupon) {
      $couponid = intval($coupon->getVar('couponid'));
        $sql = "DELETE FROM ".$this->db->prefix("xdir_coupon")." WHERE couponid = ".$couponid;
        if (!$this->db->query($sql)) {
            return false;
        }
        return true;
    }
    
    /**
    * get {@link Coupon} objects from criteria
    *
    * @param object $criteria reference to a {@link Criteria} or {@link CriteriaCompo} object
    * @param bool $as_objects if true, the returned array will be {@link Coupon} objects
    * @param bool $id_as_key if true, the returned array will have the coupon ids as key
    *
    * @return array array of {@link Coupon} objects
    */
    function &getObjects($criteria = null, $as_objects = true, $id_as_key = false) {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT cat.title AS catTitle, l.title AS linkTitle, coup.couponid, coup.heading, coup.counter, l.lid, l.cid, l.address, l.address2, l.city, l.zip, l.state, l.country, l.phone, l.fax, l.email, l.url, l.logourl, coup.description, coup.image, coup.lbr, coup.publish, coup.expire 
                FROM '.$this->db->prefix('xdir_coupon').' coup, '.$this->db->prefix('xdir_cat').' cat, '.$this->db->prefix('xdir_links').' l
                WHERE cat.cid=l.cid AND coup.lid=l.lid AND ';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            
			$sql .= ' '.$criteria->render();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            if ($as_objects) {            
                $coupon = new Coupon();
                $coupon->assignVars($myrow);
                if (!$id_as_key) {
                    $ret[] =& $coupon;
                } else {
                    $ret[$myrow['couponid']] =& $coupon;
                }
                unset($coupon);
            }
            else {
                $ret[] = $myrow;
            }
        }
        return $ret;
    }
    
    /**
    * get {@link Coupon} objects by category
    *
    * @param int $catid category id or 0 for all categories
    * @param array $catid of category ids
    * @param int $limit number of links
    * @param int $start starting point in dB
    *
    * @return array array of {@link Coupon} objects
    */
        function &getByCategory($catid = 0) {
        $ret = array();
        $limit = $start = 0;
        $now = time();
        $sql = 'SELECT cat.title AS catTitle, l.title AS linkTitle, coup.couponid, coup.heading, coup.counter, l.lid, l.cid, l.address, l.address2, l.city, l.zip, l.state, l.country, l.phone, l.fax, l.email, l.url, l.logourl, coup.description, coup.image, coup.lbr, coup.publish, coup.expire 
                FROM '.$this->db->prefix('xdir_coupon').' coup, '.$this->db->prefix('xdir_cat').' cat, '.$this->db->prefix('xdir_links').' l';
        $sql .= ' WHERE coup.lid = l.lid AND l.cid = cat.cid AND coup.publish < '.$now.' AND (coup.expire = 0 OR coup.expire > '.$now.')';
        $catid = intval($catid);
        if ($catid > 0) {            
            $sql .= " AND cat.cid = $catid";
        }
        $sql .= ' ORDER BY cat.title ASC, l.title ASC';
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow;
        }
        return $ret;
    }
  
//    function &getByCategory($catid = 0, $limit = 0, $start = 0) {
//        $ret = array();
////        $limit = $start = 0;
//        $now = time();
//        $sql = 'SELECT cat.title AS catTitle, l.title AS linkTitle, coup.couponid, coup.heading, coup.counter, l.lid, l.cid, l.cidalt1, l.cidalt2, l.cidalt3, l.cidalt4, l.address, l.address2, l.city, l.zip, l.state, l.country, l.phone, l.fax, l.email, l.url, l.logourl, l.premium, coup.description, coup.image, coup.lbr, coup.publish, coup.expire 
//                FROM '.$this->db->prefix('xdir_coupon').' coup, '.$this->db->prefix('xdir_cat').' cat, '.$this->db->prefix('xdir_links').' l';
//        $sql .= ' WHERE coup.lid = l.lid AND l.cid = cat.cid AND coup.publish < '.$now.' AND (coup.expire = 0 OR coup.expire > '.$now.')';
//        $cat_term = "";
//        if ( is_array($catid) ) {
////          echo "CATID ARRAY:<br />";
////          print_r($catid);
////          echo "<br />";
//          if ( !empty($catid) ) {
//            $lp_count = 0;
//            foreach ($catid as $multicat) {
//              $imc = intval($multicat);
//     		      $cat_term .= ($lp_count == 0) ? " AND (l.cid = ".$imc : " OR l.cid = ".$imc;
//          		$cat_term .= " OR l.cidalt1 = ".$imc;
//          		$cat_term .= " OR l.cidalt2 = ".$imc;
//          		$cat_term .= " OR l.cidalt3 = ".$imc;
//      	    	$cat_term .= " OR l.cidalt4 = ".$imc;
//              $lp_count++;
//            }
//            $cat_term .= ")";
//          }
//        } else {
////            echo "<br />CATID [".$catid."]<br />";
//          if ($catid > 0) {
//            $icid = intval($catid);
//            $cat_term .= ' AND (l.cid = '.$icid.' OR l.catalt1 = '.$icid.' OR l.catalt2 = '.$icid.' OR l.catalt3 = '.$icid.' OR l.catalt4 = '.$icid.')';
//          }
//        }
//        $sql .= $cat_term . ' ORDER BY cat.title ASC, l.title ASC';
//        $result = $this->db->query($sql, $limit, $start);
//        if (!$result) {
//            return $ret;
//        }
//        while ($myrow = $this->db->fetchArray($result)) {
//          $ret[] = $myrow;
//        }
////        echo "<br />RETURNS:<br />";
////        print_r($ret);
//        return $ret;
//    }

    /**
    * get {@link Coupon} objects by listing
    *
    * @param int $lid listing id
    * @param int $limit number of links
    * @param int $start starting point in dB
    *
    * @return array array of {@link Coupon} objects
    */
    function &getByLink($lid, $limit = 0, $start = 0 ) {
        $ret = array();
//        $limit = $start = 0;
        $now = time();
        $lid = intval($lid);
		    $sql = 'SELECT cat.title AS catTitle, l.title AS linkTitle, coup.couponid, coup.heading, coup.counter, l.lid, l.cid, l.cidalt1, l.cidalt2, l.cidalt3, l.cidalt4, l.address, l.address2, l.city, l.zip, l.state, l.country, l.phone, l.fax, l.email, l.url, l.logourl, l.premium, coup.description, coup.image, coup.lbr, coup.publish, coup.expire 
                FROM '.$this->db->prefix('xdir_coupon').' coup, '.$this->db->prefix('xdir_cat').' cat, '.$this->db->prefix('xdir_links').' l';
        $sql .= ' WHERE coup.lid = l.lid AND coup.lid='.$lid;
        $sql .= ' AND (l.cid = cat.cid OR l.cidalt1 = cat.cid OR l.cidalt2 = cat.cid OR l.cidalt3 = cat.cid OR l.cidalt4 = cat.cid)';
        $sql .= ' AND coup.publish < '.$now.' AND (coup.expire = 0 OR coup.expire > '.$now.')';
        $sql .= ' ORDER BY cat.title ASC, l.title ASC';
        $result = $this->db->query($sql, $limit, $start);
	    if (!$result) {
        return $ret;
      }
      while ($myrow = $this->db->fetchArray($result)) {
        $keep_entry = true;
        for ($i=0;$i<count($ret);$i++) {
          $keep_entry = ($myrow['lid'] === $ret[$i]['lid']) ? false : $keep_entry ;
        }
        if ($keep_entry) {
          $ret[] = $myrow;
        }
      }
      return $ret;
    }
    
    /** 
    * Returns number of coupons for a listing
    *
    * @param int $lid listing id
    *
    * @return
    */
    function getCountByLink($lid) {
        $ret = 0;
        $now = time();
        $lid = intval($lid);
		$sql = "SELECT count(*) FROM ".$this->db->prefix("xdir_coupon")." WHERE lid=".$lid.' AND publish < '.$now.' AND (expire = 0 OR expire > '.$now.')';
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        list($ret) = $this->db->fetchRow($result);
        return $ret;
    }
    
    /**
    * get {@link Coupon} object with listing info
    *
    * @param int $coupid Coupon ID
    *
    * @return object {@link Coupon}
    */
    function &getLinkedCoupon($coupid) {
        $ret = array();
        $limit = $start = 0;
        $now = time();
        $coupid = intval($coupid);
        $sql = 'SELECT cat.title AS catTitle, l.title AS linkTitle, coup.couponid, coup.heading, coup.counter, l.lid, l.cid, l.address, l.address2, l.city, l.zip, l.state, l.country, l.phone, l.fax, l.email, l.url, l.logourl, coup.description,  coup.image, coup.lbr, coup.publish, coup.expire 
                FROM '.$this->db->prefix('xdir_coupon').' coup, '.$this->db->prefix('xdir_cat').' cat, '.$this->db->prefix('xdir_links').' l';
        $sql .= ' WHERE coup.lid = l.lid AND coup.couponid='.$coupid;
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow;
        }
        return $ret;
    }
    
    /** 
    * Prepares rows from getByLink and getByCategory to be displayed
    *
    * @param array $array rows to be prepared
    *
    * @return array
    */
    function prepare2show($array, $justclean=false) {
  		global $mydirname;
      $myts =& MyTextSanitizer::getInstance();
        $ret = array();
        foreach ($array as $key => $myrow) {
            $description = $myts->displayTarea($myrow['description'], 1, 1, 1, 1, $myrow['lbr']);
            if ($myrow['expire'] > 0) {
                $expire = formatTimestamp($myrow['expire'], 's');
            }
            else {
                $expire = 0;
            }
            $tarray= array(   'lid' => $myrow['lid'],
//            $ret[$myrow['cid']]['coupons'][] = array(   'lid' => $myrow['lid'],
                                                        'linkTitle' => $myts->displayTarea($myrow['linkTitle']),
                                                        'couponid' => $myrow['couponid'],
                                                        'heading' => $myts->htmlSpecialChars($myrow['heading']),
                                                        'description' => $description,
                                                        'image' => $myrow['image'],
                                                        'address' => $myts->displayTarea($myrow['address']),
                                                        'address2' => $myts->displayTarea($myrow['address2']),
                                                        'city' => $myts->displayTarea($myrow['city']),
                                                        'state' => $myts->displayTarea($myrow['state']),
                                                        'zip' => $myts->displayTarea($myrow['zip']),
                                                        'country' => $myts->displayTarea($myrow['country']),
                                                        'phone' => $myts->displayTarea($myrow['phone']),
                                                        'fax' => $myts->displayTarea($myrow['fax']),
                                                        'email' => $myts->displayTarea($myrow['email']),
                                                        'url' => $myts->displayTarea($myrow['url']),
                                                        'logourl' => $myts->displayTarea($myrow['logourl']),
                                                        'publish' => formatTimestamp($myrow['publish'], 's'),
                                                        'expire' => $expire,
                                                        'mydirname' => $mydirname,
                                                        'counter' => intval($myrow['counter']));
            if ($justclean) {
              $ret[] = $tarray;
            } else {
              $ret[$myrow['cid']]['coupons'][] = $tarray;
              $ret[$myrow['cid']]['catTitle'] = $myrow['catTitle'];
              $ret[$myrow['cid']]['cid'] = $myrow['cid'];
            }
        }
        return $ret;
    }
    
    /**
    * Increment coupon counter
    *
    * @param int $couponid
    *
    * @return bool
    */
    function increment($couponid) {
      $couponid = intval($couponid);
        $sql = 'UPDATE '.$this->db->prefix('xdir_coupon').' SET counter=counter+1 WHERE couponid='.$couponid;
        return $this->db->queryF($sql);
    }
}
?>