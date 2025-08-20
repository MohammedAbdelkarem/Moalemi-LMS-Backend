<?php

namespace App\Services\Base;

use App\Enums\PublishStatusEnum;
use App\Enums\AccessTypeEnum;
use App\Constants\ExceptionMessages;
use App\Constants\ModelPaths;

/**
 * Class ContextService.
 */
class ContextService
{
    /**
     * Change publish status for any content model with publish_status field
     * 
     * @param mixed $model The model instance
     * @return mixed The updated model
     */
    public function changeContentPublishStatus($context)
    {
        $newStatus = $context->publish_status === PublishStatusEnum::PUBLISHED->value 
            ? PublishStatusEnum::DRAFT->value 
            : PublishStatusEnum::PUBLISHED->value;
            
        $context->update(['publish_status' => $newStatus]);

        $operation = $newStatus === PublishStatusEnum::PUBLISHED->value ? '+' : '-';

        $this->updateParentNumberOfPublishedContents($context, $operation);
    }

    /**
     * Change access type status for any content model with access_type field
     * 
     * @param mixed $context The model instance
     * @return mixed The updated model
     */
    public function changeContentAccessTypeStatus($context)
    {
        $newAccessType = $context->access_type === AccessTypeEnum::FREE->value 
            ? AccessTypeEnum::PAID->value 
            : AccessTypeEnum::FREE->value;
            
        $context->update(['access_type' => $newAccessType]);
    }

    /**
     * Update lesson duration and add it to all parent levels
     *
     * @param \App\Models\Lesson $lesson The lesson to update
     * @param int $duration The duration in minutes to add
     * @return void
     */
    public function updateLessonDurationAndParentLevels($lesson, $duration , $operation)
    {
        $parents = [
            'eLevel',
            'cLevel',
            'course',
            'subject',
            'unit',
            'subUnit',
        ];

        // Add duration to all parent levels
        foreach ($parents as $parent) {
            $this->updateDurationToParentLevel($lesson->$parent, $duration , $operation);
        }
    }

    /**
     * Add duration to a parent level if it exists
     *
     * @param mixed $parentLevel The parent level model (ELevel, CLevel, Course, Subject, Unit, SubUnit)
     * @param int $duration The duration to add
     * @return void
     */
    private function updateDurationToParentLevel($parentLevel, $duration , $operation)
    {
        $operation = ($operation == '+' ? 'increment' : 'decrement');

        if ($parentLevel) {
            $parentLevel->$operation('duration', $duration);
        }
    }

    /**
     * Update parent's number of contents for all hierarchy levels from ELevel down to SubUnit
     * 
     * @param mixed $context The model instance
     * @param string $operation '+' for increment, '-' for decrement
     */
    public function updateParentNumberOfContents($context, $operation)
    {
        $class = get_class($context);
        $operation = ($operation == '+' ? 'increment' : 'decrement');

        switch($class)
        {
            case ModelPaths::ELevel:
                // ELevel is the top level, no parent to update
                break;
            case ModelPaths::CLevel:
                $context->eLevel->$operation('number_of_contents');
                break;
            case ModelPaths::Course:
                $context->cLevel->$operation('number_of_contents');
                break;
            case ModelPaths::Subject:
                $context->course->$operation('number_of_contents');
                break;
            case ModelPaths::Unit:
                $context->subject->$operation('number_of_contents');
                break;
            case ModelPaths::SubUnit:
                $context->unit->$operation('number_of_contents');
                break;
            case ModelPaths::Lesson:
                $context->subUnit->$operation('number_of_contents');
                break;
        }
    }

    /**
     * Update parent's number of published contents for all hierarchy levels
     * 
     * @param mixed $context The model instance
     * @param string $operation '+' for increment, '-' for decrement
     */
    public function updateParentNumberOfPublishedContents($context, $operation)
    {
        $class = get_class($context);
        $operation = ($operation == '+' ? 'increment' : 'decrement');

        switch($class)
        {
            case ModelPaths::ELevel:
                // ELevel is the top level, no parent to update
                break;
            case ModelPaths::CLevel:
                $context->eLevel->$operation('number_of_published_contents');
                break;
            case ModelPaths::Course:
                $context->cLevel->$operation('number_of_published_contents');
                break;
            case ModelPaths::Subject:
                $context->course->$operation('number_of_published_contents');
                break;
            case ModelPaths::Unit:
                $context->subject->$operation('number_of_published_contents');
                break;
            case ModelPaths::SubUnit:
                $context->unit->$operation('number_of_published_contents');
                break;
            case ModelPaths::Lesson:
                $context->subUnit->$operation('number_of_published_contents');
                break;
        }
    }   
}
