<?php
namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController{

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Flash'); // Include the FlashComponent
		// Auth component allow visitors to access add action to register  and access logout action 
		$this->Auth->allow(['logout', 'add']);

    }
	
	public function login()
	{
		if ($this->request->is('post')) {
			// Auth component identify if sent user data belongs to a user
			$user = $this->Auth->identify();
			if ($user) {
				//
				$this->Auth->setUser($user);
				return $this->redirect(['controller'=>'topics','action'=>'frontfile']);
				//return $this->redirect(['']);

			}
			$this->Flash->error(__('Invalid username or password, try again.'));
		}
	}
	
	public function logout(){
		$this->Flash->success('You successfully have loged out');	
	return	$this->redirect($this->Auth->logout());
	}
	public function index()
	{
		$this->set('user',$this->Users->find('all'));		
	}
	public function view($id)
	{
		$user = $this->User->get($id);
		$this->set('user',$user);
		
	}
	public function add()
	{
		$user = $this->Users->newEntity();
		if($this->request->is('post')) {
		$user = $this->Users->patchEntity($user,$this->request->data);
			if($this->Users->save($user)){
            $this->Flash->success(__('Your account has been registered .'));
            return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to register your account.'));
		}
		$this->set('user',$user);
	}
	public function edit($id)
	{
		$user = $this->Users->get($id);
		if ($this->request->is(['post', 'put'])) {
			$this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__('Your profile data has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to update your profile.'));
		}
	
		$this->set('user', $user);		
		
	}
	public function delete($id)
	{
		$this->request->allowMethod(['post', 'delete']);
	
		$users = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user with id: {0} has been deleted.', h($id)));
			return $this->redirect(['action' => 'index']);
		}		
		
	}	
	public function search(){
		$search = $this->resquest->getQuery('q');
		$this->paginate = [
			'limit' => '5'
		];
		$posta = $this->paginate($this->posts->find()->where(function($exp , $query)
		 use($search){return $exp->like('detail','%',$search,'%');
		}));
		$this->set('topics',$topics);
	}
}
?>