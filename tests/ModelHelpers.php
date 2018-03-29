<?php

trait ModelHelpers
{
    public function assertBelongsTo($method, $parent_name, $child_name, $foreignKey = null)
    {
        $idField = $this->getIdField($method, $foreignKey);
        $this->assertRespondsTo($method, $child_name);
        $parent = factory($parent_name)->create();
        $child = factory($child_name)->create([$idField => $parent->id]);
        $child_parent_id = $child->{$method}->id;

        $message = "The '$child_name' doesn't belong to the '$method'";
        $this->assertEquals($parent->id, $child_parent_id, $message);
    }

    public function assertHasMany($method, $child_name, $parent_name)
    {
        $this->assertRespondsTo($method, $parent_name);

        $parent = factory($parent_name)->create();
        $childs = factory($child_name, 2)->create();
        $parent->{$method}()->saveMany($childs);
        $message = "The '$parent_name' doesn't have many '$method'";
        $this->assertEquals(2, $parent->{$method}->count(), $message);
    }

    public function assertBelongsToMany($method, $parent_name, $child_name)
    {
        $this->assertRespondsTo($method, $child_name);
        $parents = factory($parent_name, 2)->create();
        $child = factory($child_name)->create();
        $child->{$method}()->saveMany($parents);
        $message = "The '$child_name' doesn't have many '$method'";
        $this->assertEquals(2, $child->{$method}->count(), $message);
    }

    public function assertRespondsTo($method, $class, $message = null)
    {
        $message = $message ?: "Expected the '$class' class to have method, '$method'.";
        $this->assertTrue(
            method_exists($class, $method),
            $message
        );
    }

    /**
     * Get the snake_case field from the method
     * @param $method
     * @param null $foreignKey
     * @return string
     */
    private function getIdField($method, $foreignKey = null)
    {
        return $foreignKey ?: snake_case($method) . '_id';
    }

}