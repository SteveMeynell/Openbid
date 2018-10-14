<?php
class ModelToolSimulateData extends Model {
	
    public function simulateUser($data){
        $user = array();
        // customer info needed
        $user['firstname']              =   $data['name']['first'];
        $user['lastname']               =   $data['name']['last'];
        $user['password']               =   '123456';
        
        
        $store                          =   0;
        $language                       =   1;
        $user['customer_group_id']      =   rand(1,3);
        $user['email']                  =   $data['email'];
        $user['telephone']              =   $data['phone'];
        $user['newsletter']             =   rand(0,1);
        $user['status']                 =   '1';
        $user['approved']               =   '1';
        $user['safe']                   =   '0';
        
        // Address info needed
        $user['address'] = array();
        $user['address'][0] = array();
        $user['address'][0]['firstname']       =   $user['firstname'];
        $user['address'][0]['lastname']        =   $user['lastname'];
        $user['address'][0]['company']          =   '';
        $user['address'][0]['address_1']       =   $data['location']['street'];
        $user['address'][0]['address_2']       =   '';
        $user['address'][0]['city']           =   $data['location']['city'];
        $user['address'][0]['postcode']    =   $data['location']['postcode'];
        $zone           =   strtolower($data['location']['state']);
        $user['address'][0]['country_id']     =   '38';
        $user['address'][0]['default']          =   true;
        $this->load->model('localisation/zone');
        $zones = $this->model_localisation_zone->getZonesByCountryId($user['address'][0]['country_id']);

        $zone_ids = array_column($zones, 'name', 'zone_id');
        foreach($zone_ids as $id => $zoneName){
            if(strtolower($zoneName) == $zone){
                $user['address'][0]['zone_id'] = $id;
            }
        }
        $this->load->model('customer/customer');
        $this->model_customer_customer->addCustomer($user);
        
    }
}