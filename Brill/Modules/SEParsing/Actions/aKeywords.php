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
        require_once $this->module->pathModels . 'sep_Sets.php';
        require_once $this->module->pathModels . 'sep_Regions.php';
        require_once $this->module->pathModels . 'sep_UrlKeywords.php';
        require_once $this->module->pathDB . 'se_Stmt.php';


        $sqlT = Stmt::prepare(se_Stmt::ALL_THEMATICS, array (Stmt::ORDER => 'name'));
        $sqlR = Stmt::prepare(se_Stmt::ALL_REGIONS, array (Stmt::ORDER => 'name'));
        $listThematics = new oList(DBExt::selectToList($sqlT));
        $listRegions = new oList(DBExt::selectToList($sqlR));

        $fields['thematics'] = array('title' => 'Тематика', 'value' => '', 'data' => $listThematics, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['region'] = array('title' => 'Регион', 'value' => '', 'data' => $listRegions, 'type'=>'select', 'requried' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['set'] = array('title' => 'Название сета', 'value' => '', 'type'=>'text', 'requried' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['keywords'] = array('title' => 'Ключевик', 'value' => '', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['url'] = array('title' => 'Url', 'value' => '', 'type'=>'text', 'requried' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }

    protected function act_Thematic () {
        $this->context->setTpl('content', 'keywords_thematic_html');
        $id = (int) $this->request->get('thematic_id');
        $sql = Stmt::prepare(se_Stmt::ALL_KEYWORDS_THEMATIC, array('t_id' => $id, Stmt::ORDER => 'name'));
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->viewColumns('name', 'set');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);
        $this->context->set('h1', 'Все ключевые слова');
        $this->context->set('title', 'Ключевики');

        $tbl->setNamesColumns(array(
            'name'=>'Ключевое слово',
            'set'=>'Сет слов',
        ));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?id=#id#" ajax="1">#name#</a>');
        $tbl->addRulesView('set', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?set_id=#s_id#" ajax="1">#set#</a>');
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
        $this->context->setTpl('content', 'keywords_set_html');
        $id = (int) $this->request->get('set_id');
        
        $sql = Stmt::prepare(se_Stmt::ALL_KEYWORDS_SET, array('s_id' => $id, Stmt::ORDER => 'name'));
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->viewColumns('name', 'thematic', 'k_url');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);
        $this->context->set('h1', 'Все ключевые слова');
        $this->context->set('title', 'Ключевики');

        $tbl->setNamesColumns(array(
            'name'=>'Ключевое слово',
            'thematic'=>'Тематика',
            'k_url'=>'URL',
        ));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?id=#id#" ajax="1">#name#</a>');
        $tbl->addRulesView('thematic', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?thematic_id=#t_id#" ajax="1">#thematic#</a>');
        $this->context->set('h1', 'Ключевики сета');
        $set = new sep_Sets($id);
        $this->context->set('set', $set->name);
        $this->context->set('table', $tbl);
        $this->context->set('title', 'Ключевики');
    }

    protected function act_Pos () {
        $this->context->setTpl('content', 'keywords_pos_html');
        $id = (int) $this->request->get('id');
        $sql = Stmt::prepare(se_Stmt::URLS_AND_POS_FOR_KEYWORD, 
                array('keyword_id' => $id, Stmt::ORDER => 'pos_dot', Stmt::DIRECTION => 'DESC'));

        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->addCol('newCol');
        $this->context->set('table', $tbl);
        $tbl->viewColumns('name', 'pos', 'url', 'pos_dot', 'newCol');
        $tbl->setViewIterator(true);

        $tbl->setNamesColumns(array(
            'name'=>'Сайт',
            'pos' =>'Позиция',
            'pos_dot' => 'Позиция с точкой',
            'url' => 'Адрес',
            'newCol' => 'Разница'));
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
        $sql = Stmt::prepare(se_Stmt::ALL_KEYWORDS, array(Stmt::ORDER => 'name'));
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->viewColumns('name', 'yandex', 'set', 'thematic');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);
        $tbl->addRulesView('thematic', '<a href="newindex.php?view=keywords&thematic_id=#t_id#" ajax="1">#thematic#</a>');

        $this->context->set('h1', 'Все ключевые слова');
        $this->context->set('title', 'Ключевики');

        $tbl->setNamesColumns(array(
            'name'=>'Ключевое слово',
            'yandex'=>'Яндекс',
            'set'=>'Сет слов',
            'thematic'=>'Тематика',
        ));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?id=#id#" ajax="1">#name#</a>');
        $tbl->addRulesView('set', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?set_id=#s_id#" ajax="1">#set#</a>');
        $this->context->set('tbl', $tbl);
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
    function act_MassAdd() {
        $this->context->set('h1', 'Добавление ключевых слов');
        $this->fields['keywords'] = array('title' => 'Ключевик(и)', 'value' => '', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'Разделитель - новая строка.', 'error' => false, 'attr' => 'rows="10"', $checked = array());
        $this->runAct('Add');
    }

    function act_Add() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('keywords_add');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'keywords_add');
        }
        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {

                $kw = explode("\n", $this->request->get('keywords'));
                $k = new sep_Keywords();
                if ($this->request->is('thematics')) {
                    $thematic = new sep_Thematics((int)$this->request->get('thematics'));
                    $k->thematic_id = $thematic->id;
                }
                if ($this->request->is('set') && trim($this->request->get('set'))) {
                    $row = DBExt::getOneRow('sep_Sets', 'name', trim($this->request->get('set')));
                    if (isset($row)) {
                        $k->set_id = $row['id'];
                    } else {
                        $set = new sep_Sets();
                        $set->name = trim($this->request->get('set'));
                        $set->save();
                        $k->set_id = $set->id;
                    }
                }
                if ($this->request->is('url') && trim($this->request->get('url'))) {
                    $row = DBExt::getOneRow('sep_UrlKeywords', 'url', trim($this->request->get('url')));
                    if (isset($row)) {
                        $k->url_id = $row['id'];
                    } else {
                        $url = new sep_UrlKeywords();
                        $url->url = trim($this->request->get('url'));
                        $url->save();
                        $k->url_id = $url->id;
                    }
                }
                $k->region_id = $this->request->get('region', sep_Regions::ID_YANDEX_MOSCOW);
               
                foreach ($kw as $v) {
                    if(trim($v)) {
                        $k->name = trim($v);
                        $k->save()->reset();
                    }
                }
                $this->context->set('result', "Чтото добавляется");
                $this->context->set('form', null);
            }
        }
        $this->context->set('tpl', 'Keywords_add.tpl');
        $this->context->set('h1', 'Добавление ключевого слова', false);
        $this->context->set('title', 'Добавление ключевого слова', false);
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
