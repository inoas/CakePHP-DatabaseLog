<?php
/**
 * CakePHP DatabaseLog Plugin
 *
 * Licensed under The MIT License.
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/dereuromark/CakePHP-DatabaseLog
 */
namespace DatabaseLog\Controller\Admin;

use App\Controller\AppController;

/**
 * @property \DatabaseLog\Model\Table\DatabaseLogsTable $DatabaseLogs
 */
class LogsController extends AppController {

	/**
	 * Explicitly use the Log model.
	 *
	 * Fixes problems with the controller test.
	 *
	 * @var string
	 */
	public $modelClass = 'DatabaseLog.DatabaseLogs';

	/**
	 * Load the TimeHelper
	 *
	 * @var array
	 */
	public $helpers = ['Time'];

	/**
	 * Setup pagination
	 *
	 * @var array
	 */
	public $paginate = [
		'order' => ['DatabaseLogs.id' => 'DESC'],
		'fields' => [
			'DatabaseLogs.created',
			'DatabaseLogs.type',
			'DatabaseLogs.message',
			'DatabaseLogs.id'
		]
	];

	/**
	 * Index/Overview action
	 *
	 * @return void
	 */
	public function index() {
		$types = $this->DatabaseLogs->getTypes();
		$this->set(compact('types'));

		$conditions = $this->DatabaseLogs->textSearch();
		$type = $this->request->query('type');
		if ($type) {
			$conditions['type'] = $type;
		}
		$this->paginate = [
			'order' => ['created' => 'DESC'],
			'conditions' => $conditions
		];

		$this->set('logs', $this->paginate());
		$this->set('types', $this->DatabaseLogs->getTypes());
	}

	/**
	 * @param null|int $id The log ID to view.
	 * @return void
	 */
	public function view($id = null) {
		$log = $this->DatabaseLogs->get($id);
		$this->set('log', $log);
	}

	/**
	 * Delete action
	 *
	 * @param null|int $id The log ID to delete.
	 * @return \Cake\Network\Response|null
	 */
	public function delete($id = null) {
		$this->request->allowMethod('post');
		$log = $this->DatabaseLogs->get($id);

		if ($this->DatabaseLogs->delete($log)) {
			$this->Flash->success(__('Log deleted'));
			return $this->redirect(['action' => 'index']);
		}
		$this->Flash->error(__('Log was not deleted'));
		return $this->redirect(['action' => 'index']);
	}

	/**
	 * Reset action
	 *
	 * Deletes all log entries.
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function reset() {
		$this->request->allowMethod('post');

		$this->DatabaseLogs->deleteAll('1 = 1');

		return $this->redirect(['action' => 'index']);
	}

	/**
	 * Remove duplicates action
	 *
	 * @return \Cake\Network\Response|null
	 */
	public function removeDuplicates() {
		$this->request->allowMethod('post');

		$this->DatabaseLogs->removeDuplicates();

		$this->Flash->success(__('Duplicates have been removed.'));
		return $this->redirect(['action' => 'index']);
	}

}
