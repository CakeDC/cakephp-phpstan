<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;

class FailingControllerMethodReturnLogic extends Controller
{
    /**
     * Action with render() not returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithoutReturnRender()
    {
        $this->render('edit');
        // This code is unreachable
        $this->set('data', 'value');
    }

    /**
     * Action with redirect() not returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithoutReturnRedirect()
    {
        $this->redirect(['action' => 'index']);
        // This code is unreachable
        $this->set('data', 'value');
    }

    /**
     * Action with render() properly returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithCorrectRender()
    {
        return $this->render('edit');
    }

    /**
     * Action with redirect() properly returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithCorrectRedirect()
    {
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Action with conditional render() not returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithConditionalRenderNoReturn()
    {
        if ($this->request->is('ajax')) {
            $this->render('ajax_view');
        }
    }

    /**
     * Action with conditional redirect() not returned
     *
     * @return \Cake\Http\Response|null
     */
    public function actionWithConditionalRedirectNoReturn()
    {
        if (!$this->request->getData('id')) {
            $this->redirect(['action' => 'add']);
        }
    }
}
