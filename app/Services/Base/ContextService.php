<?php

namespace App\Services\Base;

use App\Enums\PublishStatusEnum;
use App\Enums\AccessTypeEnum;
use App\Constants\ExceptionMessages;
use App\Constants\ModelPaths;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\Lesson;

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
    public function changePublishStatus($context , $type)
    {
        $newStatus = $context->publish_status === PublishStatusEnum::PUBLISHED->value 
            ? PublishStatusEnum::DRAFT->value 
            : PublishStatusEnum::PUBLISHED->value;
            
        $context->update(['publish_status' => $newStatus]);

        $operation = $newStatus === PublishStatusEnum::PUBLISHED->value ? '+' : '-';

        if($type == 'content')
            $this->updateParentNumberOfContents($context, $operation, true);
        elseif($type == 'file')
            $this->updateParentNumberOfFiles($context, $context->context_id , $operation , true);
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
    public function updateParentNumberOfContents($context, $operation , $published = false)
    {
        $class = get_class($context);
        $operation = ($operation == '+' ? 'increment' : 'decrement');

        $field = $published ? 'number_of_published_contents' : 'number_of_contents';

        switch($class)
        {
            case ModelPaths::ELevel:
                // ELevel is the top level, no parent to update
                break;
            case ModelPaths::CLevel:
                $context->eLevel->$operation($field);
                break;
            case ModelPaths::Course:
                $context->cLevel->$operation($field);
                break;
            case ModelPaths::Subject:
                $context->course->$operation($field);
                break;
            case ModelPaths::Unit:
                $context->subject->$operation($field);
                break;
            case ModelPaths::SubUnit:
                $context->unit->$operation($field);
                break;
            case ModelPaths::Lesson:
                $context->subUnit->$operation($field);
                break;
        }
    }

    public function changeContextsPriority($contextsData , $model)
    {
        foreach ($contextsData as $contextId => $priority) {
            $context = $model::find($contextId);

            if ($context) {
                $context->update(['priority' => $priority]);
            }

            $context->save();
        }
    }

    public function updateParentNumberOfFiles($context , $id , $operation , $published = false)
    {
        // Update context number of files
        $class = $context->context_type;

        $operation = ($operation == '+' ? 'increment' : 'decrement');

        $field = $published ? 'number_of_published_files' : 'number_of_files';

        switch($class)
        {
            case ModelPaths::Subject:
                $subject = Subject::find($id);
                $subject->$operation($field);
                break;
            case ModelPaths::Unit:
                $unit = Unit::find($id);
                $unit->$operation($field);
                break;
            case ModelPaths::SubUnit:
                $subUnit = SubUnit::find($id);
                $subUnit->$operation($field);
                break;
            case ModelPaths::Lesson:
                $lesson = Lesson::find($id);
                $lesson->$operation($field);
                break;
        }

        
    }
}
