<?php
/**
 * ownCloud - files_sharing_ext
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author dauba <dauba.k@inwinstack.com>
 * @copyright dauba 2016
 */

namespace OCA\Files_Sharing_Ext\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

class ShareController extends Controller {

    public function shareLinks($data, $passwordInfo){
        $tokens = array();
        for($i = 0; $i < sizeof($data); $i++){
            $itemType = $data[$i]['type'];
            $itemSource = $data[$i]['id'];
            $itemSourceName = $data[$i]['name'];
            $permissions = $data[$i]['permissions'];
        
            $shareType = \OCP\Share::SHARE_TYPE_LINK;
            $password = null;
            $passwordChanged = ($passwordInfo['passwordChanged'] === 'true');
            if ($passwordInfo['password'] === '') {
                $passwordInfo = null;
            } 
            else {
                $password = $passwordInfo['password'];
            }
            $token = \OCP\Share::shareItem(
                $itemType,
                $itemSource,
                $shareType,
                $password,
                $permissions,
                $itemSourceName,
                (!empty($_POST['expiration']) ? new \DateTime((string)$_POST['expiration']) : null),
                $passwordChanged
            );
            $tokens[$itemSourceName] = $token;
        }
        json_encode($tokens, JSON_PRETTY_PRINT);
        return new DataResponse($tokens);
    }


}
