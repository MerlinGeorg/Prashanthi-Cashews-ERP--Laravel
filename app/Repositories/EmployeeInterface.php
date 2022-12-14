<?php
namespace App\Repositories;

interface EmployeeInterface
{
    public function getById($id);
    
    public function validate($request);

    public function store($request);
    
    public function edit($id);   

    public function update($request,$id);

    public function delete($id);
    
    public function updatePassword($request);
}