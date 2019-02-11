<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('BarCode.BarCode');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $users = $this->Users->findById($id)->orWhere(['added_by' => $id])
                                            ->toArray();

        $this->set('users', $users);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        // query to list all the activities
        $this->loadModel('Activities');
        $activities = $this->Activities->find()
                                       ->combine('id', 'name')
                                       ->toArray();
        if ($this->request->is('post')) {
            $reqData = $this->request->getData();
            $activities = $reqData['user_activities'];
            unset($reqData['user_activities']);
            $this->loadComponent('BarCode.BarCode');
            // data of activities which user registered for to do
            if(!empty($activities)){
                foreach ($activities as $activityId) {
                    $reqData['user_activities'][] = [
                                                        'activity_id' => $activityId
                                                    ];
                }
            }

            $generatorSVG = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $userBarcodeImage = $generatorSVG->getBarcode('081231723897', $generatorSVG::TYPE_CODE_128);
            $reqData['user_barcode'] = $userBarcodeImage;
            // check the list of guests if the friends array is empty then unset key corresponding to user guest.
            if(isset($reqData['friends'])){
             foreach ($reqData['friends'] as $key => $value) {
                if(empty($value['first_name']) && empty($value['last_name']) && empty($value['email'])){
                    unset($reqData['friends'][$key]);
                }else{
                    $reqData['friends'][$key]['user_activities'] = $reqData['user_activities'];
                    $reqData['friends'][$key]['user_barcode'] = $userBarcodeImage;
                }
             }

            }
            $user = $this->Users->patchEntity($user, $reqData);
            if ($this->Users->save($user)) {
                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user', 'activities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
