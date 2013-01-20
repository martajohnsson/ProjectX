<?php

require_once dirname(__file__) . '/DbHandler.php';
require_once dirname(__FILE__) . '/User.php';

class UserHandler
{

    private $_dbHandler;

    public function __construct()
    {
        $this->_dbHandler = new DbHandler();
    }

    /**
     *Check if a user exist in database
     * @param $email string Email adress to check user against
     * @return boolean true if user exist
     */
    public function doesUserExist($email)
    {
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT id FROM user_auth WHERE email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();                                        
            $stmt->store_result();

            if ($stmt->num_rows() > 0) {
                return true;
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return false;
    }

    /**
     * Generates api key
     * @return string api key
     */
    public function generateApiKey()
    {
        $key = sha1(time() . rand(0, PHP_INT_MAX));
        $results = -1;
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("SELECT api_key FROM user WHERE api_key = ?")) {
            $stmt->bind_param('s', $key);
            $stmt->execute();
            $stmt->store_result();
            $results = $stmt->num_rows();
            $stmt->close();
        }

        $this->_dbHandler->close();
        if ($results == 0) {
            return $key;
        } else {
            $this->generateApiKey();
        }
    }

    /**
     * Changes the api key of a user
     * @param int ID user id
     */
    public function changeApiKey($id)
    {
        $apiKey = $this->generateApiKey();
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("UPDATE user SET api_key = ? WHERE id = ?")) {

            $stmt->bind_param('si', $apiKey, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->affected_rows != 1) {
                $stmt->close();
                $this->_dbHandler->close();

                return false;
            }
        } else {
            $stmt->close();
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();
        return $apiKey;
    }

    /**
     * Adds a user
     * @param $identifier string
     * @param $provider string
     * @param $name string
     * @param $email string
     * @return boolean true if succsess
     */
    public function addUser($identifier, $provider, $name, $email = null, $role = 1)
    {
        $insertedKey = -1;
        $apiKey = $this->generateApiKey();
        //@TODO ÄNDRA OM TILL TRANSAKTIONER OM MÖJLIGT
        //Insert data into user table
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("INSERT INTO user (name, username, api_key, role_id) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param('ssss', $name, $email, $apiKey, $role);
            $stmt->execute();
            if ($stmt->affected_rows == null) {
                $stmt->close();
                $this->_dbHandler->close();
                return false;
            }
            $insertedKey = $stmt->insert_id;
            $stmt->close();
        } else {
            $this->_dbHandler->close();
            return false;
        }

        //Insert data into user_auth table whith key from user table
        if ($stmt = $this->_dbHandler->PrepareStatement("INSERT INTO user_auth (email, provider, identifier, user_id) VALUES (?,?,?,?)")) {
            $stmt->bind_param('sssi', $email, $provider, $identifier, $insertedKey);
            $stmt->execute();
            if ($stmt->affected_rows == null) {
                $stmt->close();
                $this->_dbHandler->close();
                return false;
            }
            $stmt->close();
        } else {
            $this->_dbHandler->close();
            return false;
        }

        $this->_dbHandler->close();
        //return id of the created user
        return $insertedKey;
    }

    /**
     * @param $email string
     * @param $identifier string
     * @param $provider string
     * @param $id int User id
     */
    public function extendUser($email, $provider, $identifier, $id)
    {
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("INSERT INTO user_auth (email, provider, identifier, user_id) VALUES(?, ?, ?, ?)")) {
            $stmt->bind_param('sssi', $email, $provider, $identifier, $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->affected_rows == 1) {
                $stmt->close();
                $this->_dbHandler->close();
                return true;
            }
            $stmt->close();
        }

        $this->_dbHandler->close();
        return false;
    }

    /**
     *Delete a user from database
     * @param int id of a user
     * @return boolean true if succsess
     */
    public function deleteUser($id)
    {   
        $this->_dbHandler->__wakeup();
        $stmt = $this->_dbHandler->PrepareStatement("DELETE u.*, a.*
                                                        FROM user_auth a
                                                        INNER JOIN user u
                                                        ON u.id = a.user_id
                                                        WHERE u.id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($stmt->affected_rows != 2) {
            $stmt->close();
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();

        return true;
    }

    /**
     * Get user from database by email
     * @param $email string
     * @return User
     */
    public function getUserByEmail($email)
    {
        $user = null;
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT user.id, user.name, user.username, user.api_key, user.role_id ,auth.email
                                                        FROM `user` as user
                                                        INNER JOIN user_auth as auth
                                                        ON user.id = auth.user_id
                                                        WHERE auth.email = ?")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();

            $stmt->bind_result($id, $name, $username, $apiKey, $role, $email);
            while ($stmt->fetch()) {
                $user = new User($id, $name, $username, $email, $apiKey, $role);
            }

            $stmt->close();
        }
        $this->_dbHandler->close();

        return $user;
    }

    public function getUsernameByID($id)
    {
        $authorName = 'errorName';
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->prepareStatement("SELECT name FROM user WHERE id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($name);
            while ($stmt->fetch()) {
                $authorName = $name;
            }
            $authorName = $name;
            $stmt->close();
        } else {
            return false;
        }
        $this->_dbHandler->close();
        return $authorName;
    }

    /**
     * Get user from database by identifier
     * @param $identifier string
     * @return User
     */
    public function getUserByIdentifier($identifier)
    {
        $user = null;
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT user.id, user.name, user.username ,auth.email, user.api_key, user.role_id
                                                        FROM `user` as user
                                                        INNER JOIN user_auth as auth
                                                        ON user.id = auth.user_id
                                                        WHERE auth.identifier = ?")) 
        {
            $stmt->bind_param('s', $identifier);
            $stmt->execute();

            $stmt->bind_result($id, $name, $username, $email, $apiKey, $role);
            while ($stmt->fetch()) {
                $user = new User($id, $name, $username, $email, $apiKey, $role);
            }

            $stmt->close();
        }
        $this->_dbHandler->close();

        return $user;
    }

    /**
     *Check if a user exist in database
     * @param $identifier string Identifier to check user against
     * @return boolean true if user exist
     */
    public function twitterExists($identifier)
    {
        $this->_dbHandler->__wakeup();
        if ($stmt = $this->_dbHandler->PrepareStatement("SELECT * FROM user_auth WHERE identifier = ?")) {

            $stmt->bind_param("s", $identifier);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                return true;
            }

            $stmt->close();

        } else {
            return null;
        }
        $this->_dbHandler->Close();
        return false;
    }

    /**
     * Get name of role from table user_role by the id
     * @param $roleId int id of role from table user_role
     * @return String name of role, false if query fails
     */
    public function getRoleByID($roleId) 
    {
        $roleName = 'none';
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement('SELECT role FROM user_role WHERE id = ? ')) {
            $stmt->bind_param('i', $roleId);
            $stmt->execute();
            $stmt->bind_result($roleName);
            $stmt->fetch();
            $stmt->close();
        } else {
            return false;
        }
        $this->_dbHandler->close();
        return $roleName;
    }

    /**
     * @return Array
     */
    public function getAllRoles()
    {
        $this->_dbHandler->__wakeup();
        $roleArr = array();
        if($stmt = $this->_dbHandler->prepareStatement('SELECT * FROM user_role')) {
            $stmt->execute();
            $stmt->bind_result($id, $role);

            while ($stmt->fetch()) {
                $roleArr[$id] = $role; 
            }
            $stmt->close();
        } else {
            return false;
        }
        $this->_dbHandler->close();
        return $roleArr;
    }

    public function changeUserRole($userId, $roleId) {
        $this->_dbHandler->__wakeup();

        if($stmt = $this->_dbHandler->prepareStatement("UPDATE user SET role_id = ? WHERE id = ?")) {
            $stmt->bind_param('ii', $roleId, $userId);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->affected_rows == 1) {
                $stmt->close();
                $this->_dbHandler->close();
                return true;
            }
        } else {
            $this->_dbHandler->close();
            return false;
        }
        $stmt->close();
        $this->_dbHandler->close();
        return false;
    }

    public function deleteEmail($id, $email)
    {
        $this->_dbHandler->__wakeup();
        if($stmt = $this->_dbHandler->prepareStatement("DELETE FROM User_auth WHERE user_id = ? AND email = ?")) {
            $stmt->bind_param('is', $id, $email);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->affected_rows == 1){
                $result = true;
            }
            $stmt->close();
        }
        $this->_dbHandler->close();
        return $result;
    }

    /**
     * Delete user data from database. Snippets och kommentarer kommer gå sönder.
     */
    public function deleteAccount($id) 
    {
        $this->_dbHandler->__wakeup();
        $result = false;
        if($stmt = $this->_dbHandler->prepareStatement("DELETE u.*,ua.* 
                                                        FROM user_auth AS ua
                                                        INNER JOIN user AS u
                                                        ON u.id = ua.user_id 
                                                        WHERE u.id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->affected_rows == 1){
                $result = true;
            }
            $stmt->close();
        }
        $this->_dbHandler->close();
        return $result;
    }

    /**
     * Remove emailadress from user but keep id so the site wont brake.
     */
    public function removeAccount($id)
    {
        //Remove data from user_auth
        $this->_dbHandler->__wakeup();
        $result = false;
        if($stmt = $this->_dbHandler->prepareStatement("DELETE FROM user_auth WHERE user_id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->affected_rows == 1){
                $result = true;
            }
            $stmt->close();
        }

        //Set data in user to 'removed'
        if($result){
            $result = false;
            $removed = 'removed';
            if($stmt = $this->_dbHandler->prepareStatement('UPDATE user SET name = ?, username = ?, api_key = ? WHERE id = ?')) {
                $stmt->bind_param('sssi',$removed, $removed, $removed, $id);
                $stmt->execute();
                $stmt->store_result();

                if($stmt->affected_rows == 1) {
                    $result = true;
                }
                $stmt->close();
            }
        }
        $this->_dbHandler->close();
        return $result;
    }

    public function getUsersEmail($id)
    {
        $this->_dbHandler->__wakeup();
        $results = array();
        if($stmt = $this->_dbHandler->prepareStatement("SELECT * from user_auth WHERE user_id = ?")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($id, $email, $provider, $identifier, $userID);
            $i = 0;
            while($stmt->fetch()) {
                $results[$i]['id'] = $id;
                $results[$i]['email'] = $email;
                $results[$i]['provider'] = $provider;
                $results[$i]['identifier'] = $identifier;
                $results[$i]['user_id'] = $userID;
                $i++;
            }
        }
        return $results;
    }
}