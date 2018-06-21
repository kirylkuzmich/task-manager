<?php

namespace Kuzmich\TaskBundle\Controller;


use Kuzmich\TaskBundle\Helper\Methods;
use Kuzmich\TaskBundle\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;



class TaskController extends Controller
{
    private $statuses = array('TODO' => 'TODO',
                            'DOING' => 'DOING',
                            'DONE' => 'DONE');

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

        $task = new Task();
        $form = $this->createFormBuilder($task, array(
            'action' => $this->generateUrl('task_new'),
            'method' => 'POST',
        ))
            ->add('name', TextType::class)
            ->add('description',TextareaType::class)
            ->add('status', ChoiceType::class, array(
                'choices'  => $this->statuses))->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $task->setDate(date("Y-m-d H:i:s"));
            Methods::postRequest('http://127.0.0.1:8000/task/new',
                array(
                    'name'=>$task->getName(),
                    'description'=> $task->getDescription(),
                    'status'=>$task->getStatus(),
                    'date'=>$task->getDate()
                ));

            return $this->redirectToRoute('kuzmich_task_homepage');
       }

        return $this->render('task/new.html.twig', array(
            'entity' => $task,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $task = Methods::getRequest('http://127.0.0.1:8000/task/'.$id.'/show');
        $form = $this->createFormBuilder($task, array(
            'action' => $this->generateUrl('task_edit', array('id'=>$id)),
            'method' => 'POST',
        ))
            ->add('status', ChoiceType::class, array(
                'choices'  => $this->statuses))
            ->add('comment',TextareaType::class, array('required'=>false ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            Methods::postRequest('http://127.0.0.1:8000/task/' .$id.'/edit',
                array(
                    'status'=>$form->getNormData()['status'],
                    'comment'=>$form->getNormData()['comment'],
                ));

            return $this->redirectToRoute('kuzmich_task_homepage');
        }
        
        $comments = $task['comments'];
        rsort($comments);

        return $this->render('task/edit.html.twig', array(
            'task'      => $task,
            'edit_form'   => $form->createView(),
            'comments' => $comments,

        ));
    }

}