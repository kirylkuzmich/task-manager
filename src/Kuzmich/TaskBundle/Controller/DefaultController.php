<?php

namespace Kuzmich\TaskBundle\Controller;

use Kuzmich\TaskBundle\Helper\Methods;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    function sortFunction( $a, $b ) {
        return -(strtotime($a['date']) - strtotime($b['date']));
    }


    public function indexAction()
    {
        $tasks = Methods::getRequest('http://127.0.0.1:8000/task/');
        $tasksTODO = array();
        $tasksDOING = array();
        $tasksDONE = array();

        usort($tasks, array($this, "sortFunction"));

        foreach ($tasks as $task)
        {
            if(strcasecmp( $task['status'], 'TODO') == 0)
            {
                $task['countComments'] = count($task['comments']);
                array_push($tasksTODO, $task);
            }

            else if(strcasecmp( $task['status'], 'DOING') == 0)
            {
                $task['countComments'] = count($task['comments']);
                array_push($tasksDOING, $task);
            }

            else if(strcasecmp( $task['status'], 'DONE') == 0)
            {
                $task['countComments'] = count($task['comments']);
                array_push($tasksDONE, $task);
            }
        }

        return $this->render('default/index.html.twig',
            array('tasksTODO' => $tasksTODO,
                'tasksDOING' => $tasksDOING,
                'tasksDONE' => $tasksDONE));
    }
}
