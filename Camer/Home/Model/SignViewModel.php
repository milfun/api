<?php
namespace Home\Model;
use Think\Model\ViewModel;
class SignViewModel extends ViewModel {
   public $viewFields = array(
     'Signlist'=>array('uid','aid'),
     'User'=>array('uid', 'headimg')
   );
 }