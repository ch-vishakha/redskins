<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Mailer\Email;
/**
 * Users Model
 *
 * @property \App\Model\Table\UserActiviesTable|\Cake\ORM\Association\HasMany $UserActivies
 * @property |\Cake\ORM\Association\HasMany $UserActivities
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{   
    protected $_content = '<img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image6.jpg" align="middle" style="width: 498px; margin: 0 0 0 100px;" class="fr-fic fr-dib">
        <br>
        <span style="font-size: 14px; margin: 0 0 0 168px;">{{first_name}} {{last_name}}</span>
                <span style="font-size: 14px;">
                    <img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image1.gif" style="width: 166px;" class="fr-fic fr-dii">&nbsp; {{user_barcode_image}}
                </span><br>
            
            <img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image5.jpg" align="middle" style="width: 498px;" class="fr-fic fr-dib">
            <br>
            <img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image4.jpg" align="middle" style="width: 498px;" class="fr-fic fr-dib">
            
            <img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image3.jpg" align="middle" style="width: 498px;" class="fr-fic fr-dib">
            <br>
            <img src="http://localhost/redskinsTrainingCamp/webroot/email_images/image2.jpg"  align="middle" style="width: 498px;" class="fr-fic fr-dib">';
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {   
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('UserActivities', [
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'added_by',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create')
            ->allowEmptyString('first_name', false);

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->requirePresence('last_name', 'create')
            ->allowEmptyString('last_name', false);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->allowEmptyString('email', false);

        $validator
            ->integer('zip_code')
            ->allowEmptyString('zip_code');

        $validator
            ->integer('no_of_guests')
            ->allowEmptyString('no_of_guests');

        $validator
            ->integer('added_by')
            ->allowEmptyString('added_by');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }


    /** 
     * Function to save guest users and fire email once the entity get saved
     * @param \Cake\Event\Event $event.
     * @return false if entity didn't save
    */

    public function afterSaveCommit($event, $entity, $options){
        $barcode = base64_encode($entity->user_barcode);
        $file = fopen(WWW_ROOT."user_barcodes/".$entity->id.'.txt',"w");    
        fwrite($file, $barcode);
        fclose($file);
        $this->_transformEmailContent($entity, $barcode);
       
        
        if(isset($entity->friends) && !empty($entity->friends)){
            foreach ($entity->friends as $key => $value) {
                $entity->friends[$key]['added_by'] = $entity->id;
            }

            $data = $entity->friends;
            $guestUsers = $this->newEntities($data);
            if(!$this->saveMany($guestUsers)){
                return false;
            }

            foreach ($guestUsers as $value) {
                $file = fopen(WWW_ROOT."user_barcodes/".$value->id.".jpg","w");    
                fwrite($file, $value->user_barcode);
                fclose($file);
                $this->_transformEmailContent($value);
            }
        }
        
        return $entity;
    }


    private function _transformEmailContent($primaryUser){

        if(isset($primaryUser->friends) && !empty($primaryUser->friends)){

            $guestUsersId = $this->find()->where(['added_by' => $primaryUser->id])
                                         ->indexBy('id')
                                         ->toArray();

            if(strpos($this->_content, 'user_barcode_image')){
                $guestUserContent = '{{user_barcode_image}}<br><div class="row"><h4>GUEST BARCODES</h4><hr><br>{{guest_user_barcode}}</div>';
                $this->_content = str_replace('{{user_barcode_image}}', $guestUserContent, $this->_content);
             }

             $count = 0;
             for ($i = 0; $i <= count($guestUsersId); $i++) {
                if(strpos($this->_content, 'guest_user_barcode')){
                    if($count < count($guestUsersId)){
                        $this->_content = str_replace('{{guest_user_barcode}}', '{{guest_user_barcode}}<br>{{guest_user_barcode_'.$guestUsersId[$i].'}}', $this->_content);
                    }else{
                        $this->_content = str_replace('{{guest_user_barcode}}', ' ', $this->_content);
                    }
                    $count++;
                }
                // get user specific barcode from the folder
                if(isset($guestUsersId[$i])){
                 $hashData['guest_user_barcode_'.$guestUsersId[$i]] = base64_encode($generator->getBarcode($guestUsersId[$i]->email, $generator::TYPE_CODE_128));
                } 
            }
        }

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $hashData = [
                        'first_name' => $primaryUser->first_name,
                        'last_name' => $primaryUser->last_name,
                        'user_barcode_image' =>base64_encode($generator->getBarcode($primaryUser->email, $generator::TYPE_CODE_128))
                    ];

        $data = $this->_substitute($this->_content, $hashData);
        
        $email = new Email('default');
        $email->to($primaryUser->email)
              ->emailFormat('html')
              ->subject('RedSkins Training Camp Pass')
              ->send($data);

    }

    private function _substitute($content, $hash){
        $content = str_replace('"', "'", $content);
        foreach ($hash as $key => $value) {
            if(!is_array($value)){
                $value = trim($value);
                $value = str_replace('"', '\"', $value);
                $placeholder = sprintf('{{%s}}', $key);
                $placeholder = sprintf('{{%s}}', $key);

                if(strpos($placeholder, 'user_barcode_image')){

                    $content = str_replace($placeholder, "<img src=data:image/png;base64,".$value .'style' => 'width:171px; height:166px;'">", $content);
                    // $content = str_replace($placeholder, , $content);

                }else{
                    $content = str_replace($placeholder, $value, $content);
                }
            }
        }
        return $content;
    }

    
}
