<?php
/**
 * Description of Keywords
 *
 * @author almaz
 */


class aKeywords extends Action {
    protected $defaultAct = 'view';

    protected function configure() {
        require_once $this->module->pathModels . 'sep_Keywords.php';
        require_once $this->module->pathModels . 'sep_Thematics.php';
        require_once MODULES_PATH . 'SEParsing/DB/Stmt.php';
    }

    protected function act_Thematic () {
        $this->context->setTpl('content', 'keywords_thematic_html');
        $id = (int) $this->request->get('thematic_id');
        $sql = Stmt::getSql('ALL_KEYWORDS_THEMATIC', array('t_id' => $id));
        $tbl = new oTable(DBExt::selectToTable($sql. ' order by name ASC'));
        $tbl->viewColumns('name', 'set');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);
        $this->context->set('h1', 'Все ключевые слова');
        $this->context->set('title', 'Ключевики');

        $tbl->setNamesColumns(array(
            'name'=>'Ключевое слово',
            'set'=>'Сет слов',
        ));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?id=#id#">#name#</a>');
        $tbl->addRulesView('set', '<a href="newindex.php?view=sites&set_id=#s_id#">#set#</a>');
        $this->context->set('h1', 'Ключевики по тематике');
        $thematic = new sep_Thematics();
        $thematic->getObject($id);
        $this->context->set('thematic', $thematic->name);
        $this->context->set('table', $tbl);
        $this->context->set('title', 'Ключевики');
    }

/*
 * set_id - ключевики, с урлами, позицийями, разницой позиций, с разделеителем(кв)
 * thematic_id - все ключевики одной тематики
 * site_id
 * sep_thematic - группировка по тематикам, включаем разделители
 * sep_set - группировка по сетам
 *
 *
 */
    protected function act_Set () {
        #$setId = $this->request->
    }
    protected function act_Pos () {
        $this->context->setTpl('content', 'keywords_pos_html');
        $id = (int) $this->request->get('id');
        $sql = Stmt::getSql('URLS_AND_POS_FOR_KEYWORD', array('keyword_id' => $id));

        $tbl = new oTable(DBExt::selectToTable($sql. ' order by pos_dot DESC'));
        $tbl->addCol('newCol');
        $this->context->set('table', $tbl);
        $tbl->viewColumns('name', 'pos', 'url', 'pos_dot', 'newCol');
        $tbl->setViewIterator(true);

        $tbl->setNamesColumns(array(
            'name'=>'Сайт',
            'pos' =>'Позиция',
            'pos_dot' => 'Позиция с точкой',
            'url' => 'Адрес',
            'newCol' => 'Новый столбец'));
        //$tbl->addMap('url', 'urldecode');
        $posCol = $tbl->getCol('pos');
        $newCol = array();
        $v = $tbl->getValues();
       // Log::dump($v);
        $i = 0;
        foreach ($posCol as &$pp) {
           if ($v[$i]['pos_dot'] && $v[$i]['pos']) {
                $newCol[$i] = $v[$i]['pos_dot']-$v[$i]['pos'];
            } else {
                 $newCol[$i] = '&mdash;';
            }
            $i++;
            if ($pp == 0) {
                $pp = '&mdash;';
            }
        }
        $tbl->setCol('pos', $posCol);

        $urlCol = $tbl->getCol('url');

        foreach ($urlCol as &$val) {
            $val = TFormat::cutCenter(urldecode($val), 65);
        }

        $tbl->setCol('url', $urlCol);
        $tbl->setCol('newCol', $newCol);

        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $keyword = new sep_Keywords();
        $keyword->getObject($id);
        $this->context->set('h1', 'Статистика по ключевому слову');
        $this->context->set('keyword', $keyword->name);
        $this->context->set('title', 'Ключевики');
    }


    protected function act_All () {
        $sql = Stmt::getSql('ALL_KEYWORDS');
        $tbl = new oTable(DBExt::selectToTable($sql. ' order by name ASC'));
        $tbl->viewColumns('name', 'yandex', 'set', 'thematic');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);
        $tbl->addRulesView('thematic', '<a href="newindex.php?view=keywords&thematic_id=#t_id#">#thematic#</a>');

        $this->context->set('h1', 'Все ключевые слова');
        $this->context->set('title', 'Ключевики');

        $tbl->setNamesColumns(array(
            'name'=>'Ключевое слово',
            'yandex'=>'Яндекс',
            'set'=>'Сет слов',
            'thematic'=>'Тематика',
        ));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?id=#id#">#name#</a>');
        $tbl->addRulesView('set', '<a href="newindex.php?view=sites&set_id=#s_id#">#set#</a>');
        $this->context->set('table', $tbl);
    }
    public function act_View() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('keywords_list');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'keywords_list');
        }

        if ($this->request->is('id')) {
            $this->runAct('pos');
        } else if ($this->request->is('set_id')) {
            $this->runAct('set');
        } else if ($this->request->is('thematic_id')) {
            $this->runAct('thematic');
        } else if($this->request->is('site')) {
            $this->runAct('site');
        } else {
             $this->runAct('all');
        }
//        if ($this->request->is('group')) {
//            switch ($this->request->get('group')) {
//                case 'thematic':
//                    $this->runAct('set');
//                break;
//                case 'set':
//                break;
//            }
//        }

       
    }

    function act_Add() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('keywords_add');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'keywords_add');
        }

        $sqlT = Stmt::getSql('ALL_THEMATICS');
        $listThematics = new oList(DBExt::selectToList($sqlT. ' order by name ASC'), array(0 => ''));

        $fields['thematics'] = array('title' => 'Тематика', 'value' => $listThematics, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['keywords'] = array('title' => 'Ключевик(и)', 'value' => '', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows="10"', $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        if ($this->request->is('keywords')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {

                $kw = explode("\n", $request->get('keywords_text'));
                $k = new sep_Keywords();

                if ($request->is('thematic_id')) {
                    $k->thematic_id = (int) $this->request->get('thematic_id');
                }
                if ($request->is('name_set') && trim($request->get('name_set'))) {
                    $set = new Sets();
                    if (!$set->getObject($request->get('name_set'))) {
                       $set->name = $request->get('name_set');
                       $set->id = $set->add();
                    }
                    $k->set_id = $set->id;
                }
                $k->region_id = 213;
                foreach ($kw as $v) {
                    if(trim($v)) {
                     $k->name = trim($v);
                     $k->add();
                    }
                }
                $context->set('result', "Чтото добавляется");
                $context->set('form', false);

            }

        }

        $this->context->set('tpl', 'Keywords_add.tpl');
        $this->context->set('h1', 'Добавление ключевых слов');
        $this->context->set('title', 'Добавление ключевых слов');
    }


    function _parent(InternalRoute $iRoute = null) {
        if (!$iRoute) {
            $iRoute = new InternalRoute();
            $iRoute->module = 'Pages';
            $iRoute->action = 'Pages';
        }
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runParentAct();
    }

}
