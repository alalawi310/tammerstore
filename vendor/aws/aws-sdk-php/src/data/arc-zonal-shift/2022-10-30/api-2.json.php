<?php
// This file was auto-generated from sdk-root/src/data/arc-zonal-shift/2024-10-30/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2024-10-30', 'endpointPrefix' => 'arc-zonal-shift', 'jsonVersion' => '1.1', 'protocol' => 'rest-json', 'protocols' => [ 'rest-json', ], 'serviceFullName' => 'AWS ARC - Zonal Shift', 'serviceId' => 'ARC Zonal Shift', 'signatureVersion' => 'v4', 'signingName' => 'arc-zonal-shift', 'uid' => 'arc-zonal-shift-2024-10-30', 'auth' => [ 'aws.auth#sigv4', ], ], 'operations' => [ 'CancelZonalShift' => [ 'name' => 'CancelZonalShift', 'http' => [ 'method' => 'DELETE', 'requestUri' => '/zonalshifts/{zonalShiftId}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'CancelZonalShiftRequest', ], 'output' => [ 'shape' => 'ZonalShift', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'CreatePracticeRunConfiguration' => [ 'name' => 'CreatePracticeRunConfiguration', 'http' => [ 'method' => 'POST', 'requestUri' => '/configuration', 'responseCode' => 201, ], 'input' => [ 'shape' => 'CreatePracticeRunConfigurationRequest', ], 'output' => [ 'shape' => 'CreatePracticeRunConfigurationResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'DeletePracticeRunConfiguration' => [ 'name' => 'DeletePracticeRunConfiguration', 'http' => [ 'method' => 'DELETE', 'requestUri' => '/configuration/{resourceIdentifier}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'DeletePracticeRunConfigurationRequest', ], 'output' => [ 'shape' => 'DeletePracticeRunConfigurationResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], 'idempotent' => true, ], 'GetAutoshiftObserverNotificationStatus' => [ 'name' => 'GetAutoshiftObserverNotificationStatus', 'http' => [ 'method' => 'GET', 'requestUri' => '/autoshift-observer-notification', 'responseCode' => 200, ], 'input' => [ 'shape' => 'GetAutoshiftObserverNotificationStatusRequest', ], 'output' => [ 'shape' => 'GetAutoshiftObserverNotificationStatusResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'GetManagedResource' => [ 'name' => 'GetManagedResource', 'http' => [ 'method' => 'GET', 'requestUri' => '/managedresources/{resourceIdentifier}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'GetManagedResourceRequest', ], 'output' => [ 'shape' => 'GetManagedResourceResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'ListAutoshifts' => [ 'name' => 'ListAutoshifts', 'http' => [ 'method' => 'GET', 'requestUri' => '/autoshifts', 'responseCode' => 200, ], 'input' => [ 'shape' => 'ListAutoshiftsRequest', ], 'output' => [ 'shape' => 'ListAutoshiftsResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'ListManagedResources' => [ 'name' => 'ListManagedResources', 'http' => [ 'method' => 'GET', 'requestUri' => '/managedresources', 'responseCode' => 200, ], 'input' => [ 'shape' => 'ListManagedResourcesRequest', ], 'output' => [ 'shape' => 'ListManagedResourcesResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'ListZonalShifts' => [ 'name' => 'ListZonalShifts', 'http' => [ 'method' => 'GET', 'requestUri' => '/zonalshifts', 'responseCode' => 200, ], 'input' => [ 'shape' => 'ListZonalShiftsRequest', ], 'output' => [ 'shape' => 'ListZonalShiftsResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'StartZonalShift' => [ 'name' => 'StartZonalShift', 'http' => [ 'method' => 'POST', 'requestUri' => '/zonalshifts', 'responseCode' => 201, ], 'input' => [ 'shape' => 'StartZonalShiftRequest', ], 'output' => [ 'shape' => 'ZonalShift', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'UpdateAutoshiftObserverNotificationStatus' => [ 'name' => 'UpdateAutoshiftObserverNotificationStatus', 'http' => [ 'method' => 'PUT', 'requestUri' => '/autoshift-observer-notification', 'responseCode' => 200, ], 'input' => [ 'shape' => 'UpdateAutoshiftObserverNotificationStatusRequest', ], 'output' => [ 'shape' => 'UpdateAutoshiftObserverNotificationStatusResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], 'idempotent' => true, ], 'UpdatePracticeRunConfiguration' => [ 'name' => 'UpdatePracticeRunConfiguration', 'http' => [ 'method' => 'PATCH', 'requestUri' => '/configuration/{resourceIdentifier}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'UpdatePracticeRunConfigurationRequest', ], 'output' => [ 'shape' => 'UpdatePracticeRunConfigurationResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], 'UpdateZonalAutoshiftConfiguration' => [ 'name' => 'UpdateZonalAutoshiftConfiguration', 'http' => [ 'method' => 'PUT', 'requestUri' => '/managedresources/{resourceIdentifier}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'UpdateZonalAutoshiftConfigurationRequest', ], 'output' => [ 'shape' => 'UpdateZonalAutoshiftConfigurationResponse', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], 'idempotent' => true, ], 'UpdateZonalShift' => [ 'name' => 'UpdateZonalShift', 'http' => [ 'method' => 'PATCH', 'requestUri' => '/zonalshifts/{zonalShiftId}', 'responseCode' => 200, ], 'input' => [ 'shape' => 'UpdateZonalShiftRequest', ], 'output' => [ 'shape' => 'ZonalShift', ], 'errors' => [ [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ConflictException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ThrottlingException', ], [ 'shape' => 'ValidationException', ], [ 'shape' => 'AccessDeniedException', ], ], ], ], 'shapes' => [ 'AccessDeniedException' => [ 'type' => 'structure', 'members' => [ 'message' => [ 'shape' => 'String', ], ], 'error' => [ 'httpStatusCode' => 403, 'senderFault' => true, ], 'exception' => true, ], 'AppliedStatus' => [ 'type' => 'string', 'enum' => [ 'APPLIED', 'NOT_APPLIED', ], ], 'AppliedWeights' => [ 'type' => 'map', 'key' => [ 'shape' => 'AvailabilityZone', ], 'value' => [ 'shape' => 'Weight', ], ], 'AutoshiftAppliedStatus' => [ 'type' => 'string', 'enum' => [ 'APPLIED', 'NOT_APPLIED', ], ], 'AutoshiftExecutionStatus' => [ 'type' => 'string', 'enum' => [ 'ACTIVE', 'COMPLETED', ], ], 'AutoshiftInResource' => [ 'type' => 'structure', 'required' => [ 'appliedStatus', 'awayFrom', 'startTime', ], 'members' => [ 'appliedStatus' => [ 'shape' => 'AutoshiftAppliedStatus', ], 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'startTime' => [ 'shape' => 'StartTime', ], ], ], 'AutoshiftObserverNotificationStatus' => [ 'type' => 'string', 'enum' => [ 'ENABLED', 'DISABLED', ], ], 'AutoshiftSummaries' => [ 'type' => 'list', 'member' => [ 'shape' => 'AutoshiftSummary', ], ], 'AutoshiftSummary' => [ 'type' => 'structure', 'required' => [ 'awayFrom', 'endTime', 'startTime', 'status', ], 'members' => [ 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'endTime' => [ 'shape' => 'ExpiryTime', ], 'startTime' => [ 'shape' => 'StartTime', ], 'status' => [ 'shape' => 'AutoshiftExecutionStatus', ], ], ], 'AutoshiftsInResource' => [ 'type' => 'list', 'member' => [ 'shape' => 'AutoshiftInResource', ], ], 'AvailabilityZone' => [ 'type' => 'string', 'max' => 20, 'min' => 0, ], 'AvailabilityZones' => [ 'type' => 'list', 'member' => [ 'shape' => 'AvailabilityZone', ], ], 'BlockedDate' => [ 'type' => 'string', 'max' => 10, 'min' => 10, 'pattern' => '^[0-9]{4}-[0-9]{2}-[0-9]{2}$', ], 'BlockedDates' => [ 'type' => 'list', 'member' => [ 'shape' => 'BlockedDate', ], 'max' => 15, 'min' => 0, ], 'BlockedWindow' => [ 'type' => 'string', 'max' => 19, 'min' => 19, 'pattern' => '^(Mon|Tue|Wed|Thu|Fri|Sat|Sun):[0-9]{2}:[0-9]{2}-(Mon|Tue|Wed|Thu|Fri|Sat|Sun):[0-9]{2}:[0-9]{2}$', ], 'BlockedWindows' => [ 'type' => 'list', 'member' => [ 'shape' => 'BlockedWindow', ], 'max' => 15, 'min' => 0, ], 'CancelZonalShiftRequest' => [ 'type' => 'structure', 'required' => [ 'zonalShiftId', ], 'members' => [ 'zonalShiftId' => [ 'shape' => 'ZonalShiftId', 'location' => 'uri', 'locationName' => 'zonalShiftId', ], ], ], 'ConflictException' => [ 'type' => 'structure', 'required' => [ 'message', 'reason', ], 'members' => [ 'message' => [ 'shape' => 'String', ], 'reason' => [ 'shape' => 'ConflictExceptionReason', ], 'zonalShiftId' => [ 'shape' => 'String', ], ], 'error' => [ 'httpStatusCode' => 409, 'senderFault' => true, ], 'exception' => true, ], 'ConflictExceptionReason' => [ 'type' => 'string', 'enum' => [ 'ZonalShiftAlreadyExists', 'ZonalShiftStatusNotActive', 'SimultaneousZonalShiftsConflict', 'PracticeConfigurationAlreadyExists', 'AutoShiftEnabled', 'PracticeConfigurationDoesNotExist', ], ], 'ControlCondition' => [ 'type' => 'structure', 'required' => [ 'alarmIdentifier', 'type', ], 'members' => [ 'alarmIdentifier' => [ 'shape' => 'ResourceArn', ], 'type' => [ 'shape' => 'ControlConditionType', ], ], ], 'ControlConditionType' => [ 'type' => 'string', 'enum' => [ 'CLOUDWATCH', ], ], 'ControlConditions' => [ 'type' => 'list', 'member' => [ 'shape' => 'ControlCondition', ], 'max' => 1, 'min' => 1, ], 'CreatePracticeRunConfigurationRequest' => [ 'type' => 'structure', 'required' => [ 'outcomeAlarms', 'resourceIdentifier', ], 'members' => [ 'blockedDates' => [ 'shape' => 'BlockedDates', ], 'blockedWindows' => [ 'shape' => 'BlockedWindows', ], 'blockingAlarms' => [ 'shape' => 'ControlConditions', ], 'outcomeAlarms' => [ 'shape' => 'ControlConditions', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], ], ], 'CreatePracticeRunConfigurationResponse' => [ 'type' => 'structure', 'required' => [ 'arn', 'name', 'practiceRunConfiguration', 'zonalAutoshiftStatus', ], 'members' => [ 'arn' => [ 'shape' => 'ResourceArn', ], 'name' => [ 'shape' => 'ResourceName', ], 'practiceRunConfiguration' => [ 'shape' => 'PracticeRunConfiguration', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], ], ], 'DeletePracticeRunConfigurationRequest' => [ 'type' => 'structure', 'required' => [ 'resourceIdentifier', ], 'members' => [ 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', 'location' => 'uri', 'locationName' => 'resourceIdentifier', ], ], ], 'DeletePracticeRunConfigurationResponse' => [ 'type' => 'structure', 'required' => [ 'arn', 'name', 'zonalAutoshiftStatus', ], 'members' => [ 'arn' => [ 'shape' => 'ResourceArn', ], 'name' => [ 'shape' => 'ResourceName', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], ], ], 'ExpiresIn' => [ 'type' => 'string', 'max' => 5, 'min' => 2, 'pattern' => '^([1-9][0-9]*)(m|h)$', ], 'ExpiryTime' => [ 'type' => 'timestamp', ], 'GetAutoshiftObserverNotificationStatusRequest' => [ 'type' => 'structure', 'members' => [], ], 'GetAutoshiftObserverNotificationStatusResponse' => [ 'type' => 'structure', 'required' => [ 'status', ], 'members' => [ 'status' => [ 'shape' => 'AutoshiftObserverNotificationStatus', ], ], ], 'GetManagedResourceRequest' => [ 'type' => 'structure', 'required' => [ 'resourceIdentifier', ], 'members' => [ 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', 'location' => 'uri', 'locationName' => 'resourceIdentifier', ], ], ], 'GetManagedResourceResponse' => [ 'type' => 'structure', 'required' => [ 'appliedWeights', 'zonalShifts', ], 'members' => [ 'appliedWeights' => [ 'shape' => 'AppliedWeights', ], 'arn' => [ 'shape' => 'ResourceArn', ], 'autoshifts' => [ 'shape' => 'AutoshiftsInResource', ], 'name' => [ 'shape' => 'ResourceName', ], 'practiceRunConfiguration' => [ 'shape' => 'PracticeRunConfiguration', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], 'zonalShifts' => [ 'shape' => 'ZonalShiftsInResource', ], ], ], 'InternalServerException' => [ 'type' => 'structure', 'members' => [ 'message' => [ 'shape' => 'String', ], ], 'error' => [ 'httpStatusCode' => 500, ], 'exception' => true, 'fault' => true, ], 'ListAutoshiftsRequest' => [ 'type' => 'structure', 'members' => [ 'maxResults' => [ 'shape' => 'MaxResults', 'location' => 'querystring', 'locationName' => 'maxResults', ], 'nextToken' => [ 'shape' => 'String', 'location' => 'querystring', 'locationName' => 'nextToken', ], 'status' => [ 'shape' => 'AutoshiftExecutionStatus', 'location' => 'querystring', 'locationName' => 'status', ], ], ], 'ListAutoshiftsResponse' => [ 'type' => 'structure', 'members' => [ 'items' => [ 'shape' => 'AutoshiftSummaries', ], 'nextToken' => [ 'shape' => 'String', ], ], ], 'ListManagedResourcesRequest' => [ 'type' => 'structure', 'members' => [ 'maxResults' => [ 'shape' => 'MaxResults', 'location' => 'querystring', 'locationName' => 'maxResults', ], 'nextToken' => [ 'shape' => 'String', 'location' => 'querystring', 'locationName' => 'nextToken', ], ], ], 'ListManagedResourcesResponse' => [ 'type' => 'structure', 'required' => [ 'items', ], 'members' => [ 'items' => [ 'shape' => 'ManagedResourceSummaries', ], 'nextToken' => [ 'shape' => 'String', ], ], ], 'ListZonalShiftsRequest' => [ 'type' => 'structure', 'members' => [ 'maxResults' => [ 'shape' => 'MaxResults', 'location' => 'querystring', 'locationName' => 'maxResults', ], 'nextToken' => [ 'shape' => 'String', 'location' => 'querystring', 'locationName' => 'nextToken', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', 'location' => 'querystring', 'locationName' => 'resourceIdentifier', ], 'status' => [ 'shape' => 'ZonalShiftStatus', 'location' => 'querystring', 'locationName' => 'status', ], ], ], 'ListZonalShiftsResponse' => [ 'type' => 'structure', 'members' => [ 'items' => [ 'shape' => 'ZonalShiftSummaries', ], 'nextToken' => [ 'shape' => 'String', ], ], ], 'ManagedResourceSummaries' => [ 'type' => 'list', 'member' => [ 'shape' => 'ManagedResourceSummary', ], ], 'ManagedResourceSummary' => [ 'type' => 'structure', 'required' => [ 'availabilityZones', ], 'members' => [ 'appliedWeights' => [ 'shape' => 'AppliedWeights', ], 'arn' => [ 'shape' => 'ResourceArn', ], 'autoshifts' => [ 'shape' => 'AutoshiftsInResource', ], 'availabilityZones' => [ 'shape' => 'AvailabilityZones', ], 'name' => [ 'shape' => 'ResourceName', ], 'practiceRunStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], 'zonalShifts' => [ 'shape' => 'ZonalShiftsInResource', ], ], ], 'MaxResults' => [ 'type' => 'integer', 'box' => true, 'max' => 100, 'min' => 1, ], 'PracticeRunConfiguration' => [ 'type' => 'structure', 'required' => [ 'outcomeAlarms', ], 'members' => [ 'blockedDates' => [ 'shape' => 'BlockedDates', ], 'blockedWindows' => [ 'shape' => 'BlockedWindows', ], 'blockingAlarms' => [ 'shape' => 'ControlConditions', ], 'outcomeAlarms' => [ 'shape' => 'ControlConditions', ], ], ], 'PracticeRunOutcome' => [ 'type' => 'string', 'enum' => [ 'FAILED', 'INTERRUPTED', 'PENDING', 'SUCCEEDED', ], ], 'ResourceArn' => [ 'type' => 'string', 'max' => 1024, 'min' => 8, 'pattern' => '^arn:.*$', ], 'ResourceIdentifier' => [ 'type' => 'string', 'max' => 1024, 'min' => 8, ], 'ResourceName' => [ 'type' => 'string', 'max' => 256, 'min' => 1, ], 'ResourceNotFoundException' => [ 'type' => 'structure', 'required' => [ 'message', ], 'members' => [ 'message' => [ 'shape' => 'String', ], ], 'error' => [ 'httpStatusCode' => 404, 'senderFault' => true, ], 'exception' => true, ], 'StartTime' => [ 'type' => 'timestamp', ], 'StartZonalShiftRequest' => [ 'type' => 'structure', 'required' => [ 'awayFrom', 'comment', 'expiresIn', 'resourceIdentifier', ], 'members' => [ 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'comment' => [ 'shape' => 'ZonalShiftComment', ], 'expiresIn' => [ 'shape' => 'ExpiresIn', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], ], ], 'String' => [ 'type' => 'string', ], 'ThrottlingException' => [ 'type' => 'structure', 'members' => [ 'message' => [ 'shape' => 'String', ], ], 'error' => [ 'httpStatusCode' => 429, 'senderFault' => true, ], 'exception' => true, ], 'UpdateAutoshiftObserverNotificationStatusRequest' => [ 'type' => 'structure', 'required' => [ 'status', ], 'members' => [ 'status' => [ 'shape' => 'AutoshiftObserverNotificationStatus', ], ], ], 'UpdateAutoshiftObserverNotificationStatusResponse' => [ 'type' => 'structure', 'required' => [ 'status', ], 'members' => [ 'status' => [ 'shape' => 'AutoshiftObserverNotificationStatus', ], ], ], 'UpdatePracticeRunConfigurationRequest' => [ 'type' => 'structure', 'required' => [ 'resourceIdentifier', ], 'members' => [ 'blockedDates' => [ 'shape' => 'BlockedDates', ], 'blockedWindows' => [ 'shape' => 'BlockedWindows', ], 'blockingAlarms' => [ 'shape' => 'ControlConditions', ], 'outcomeAlarms' => [ 'shape' => 'ControlConditions', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', 'location' => 'uri', 'locationName' => 'resourceIdentifier', ], ], ], 'UpdatePracticeRunConfigurationResponse' => [ 'type' => 'structure', 'required' => [ 'arn', 'name', 'practiceRunConfiguration', 'zonalAutoshiftStatus', ], 'members' => [ 'arn' => [ 'shape' => 'ResourceArn', ], 'name' => [ 'shape' => 'ResourceName', ], 'practiceRunConfiguration' => [ 'shape' => 'PracticeRunConfiguration', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], ], ], 'UpdateZonalAutoshiftConfigurationRequest' => [ 'type' => 'structure', 'required' => [ 'resourceIdentifier', 'zonalAutoshiftStatus', ], 'members' => [ 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', 'location' => 'uri', 'locationName' => 'resourceIdentifier', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], ], ], 'UpdateZonalAutoshiftConfigurationResponse' => [ 'type' => 'structure', 'required' => [ 'resourceIdentifier', 'zonalAutoshiftStatus', ], 'members' => [ 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], 'zonalAutoshiftStatus' => [ 'shape' => 'ZonalAutoshiftStatus', ], ], ], 'UpdateZonalShiftRequest' => [ 'type' => 'structure', 'required' => [ 'zonalShiftId', ], 'members' => [ 'comment' => [ 'shape' => 'ZonalShiftComment', ], 'expiresIn' => [ 'shape' => 'ExpiresIn', ], 'zonalShiftId' => [ 'shape' => 'ZonalShiftId', 'location' => 'uri', 'locationName' => 'zonalShiftId', ], ], ], 'ValidationException' => [ 'type' => 'structure', 'required' => [ 'message', 'reason', ], 'members' => [ 'message' => [ 'shape' => 'String', ], 'reason' => [ 'shape' => 'ValidationExceptionReason', ], ], 'error' => [ 'httpStatusCode' => 400, 'senderFault' => true, ], 'exception' => true, ], 'ValidationExceptionReason' => [ 'type' => 'string', 'enum' => [ 'InvalidExpiresIn', 'InvalidStatus', 'MissingValue', 'InvalidToken', 'InvalidResourceIdentifier', 'InvalidAz', 'UnsupportedAz', 'InvalidAlarmCondition', 'InvalidConditionType', 'InvalidPracticeBlocker', ], ], 'Weight' => [ 'type' => 'float', 'box' => true, 'max' => 1.0, 'min' => 0.0, ], 'ZonalAutoshiftStatus' => [ 'type' => 'string', 'enum' => [ 'ENABLED', 'DISABLED', ], ], 'ZonalShift' => [ 'type' => 'structure', 'required' => [ 'awayFrom', 'comment', 'expiryTime', 'resourceIdentifier', 'startTime', 'status', 'zonalShiftId', ], 'members' => [ 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'comment' => [ 'shape' => 'ZonalShiftComment', ], 'expiryTime' => [ 'shape' => 'ExpiryTime', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], 'startTime' => [ 'shape' => 'StartTime', ], 'status' => [ 'shape' => 'ZonalShiftStatus', ], 'zonalShiftId' => [ 'shape' => 'ZonalShiftId', ], ], ], 'ZonalShiftComment' => [ 'type' => 'string', 'max' => 128, 'min' => 0, ], 'ZonalShiftId' => [ 'type' => 'string', 'max' => 36, 'min' => 6, 'pattern' => '^[A-Za-z0-9-]+$', ], 'ZonalShiftInResource' => [ 'type' => 'structure', 'required' => [ 'appliedStatus', 'awayFrom', 'comment', 'expiryTime', 'resourceIdentifier', 'startTime', 'zonalShiftId', ], 'members' => [ 'appliedStatus' => [ 'shape' => 'AppliedStatus', ], 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'comment' => [ 'shape' => 'ZonalShiftComment', ], 'expiryTime' => [ 'shape' => 'ExpiryTime', ], 'practiceRunOutcome' => [ 'shape' => 'PracticeRunOutcome', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], 'startTime' => [ 'shape' => 'StartTime', ], 'zonalShiftId' => [ 'shape' => 'ZonalShiftId', ], ], ], 'ZonalShiftStatus' => [ 'type' => 'string', 'enum' => [ 'ACTIVE', 'EXPIRED', 'CANCELED', ], ], 'ZonalShiftSummaries' => [ 'type' => 'list', 'member' => [ 'shape' => 'ZonalShiftSummary', ], ], 'ZonalShiftSummary' => [ 'type' => 'structure', 'required' => [ 'awayFrom', 'comment', 'expiryTime', 'resourceIdentifier', 'startTime', 'status', 'zonalShiftId', ], 'members' => [ 'awayFrom' => [ 'shape' => 'AvailabilityZone', ], 'comment' => [ 'shape' => 'ZonalShiftComment', ], 'expiryTime' => [ 'shape' => 'ExpiryTime', ], 'practiceRunOutcome' => [ 'shape' => 'PracticeRunOutcome', ], 'resourceIdentifier' => [ 'shape' => 'ResourceIdentifier', ], 'startTime' => [ 'shape' => 'StartTime', ], 'status' => [ 'shape' => 'ZonalShiftStatus', ], 'zonalShiftId' => [ 'shape' => 'ZonalShiftId', ], ], ], 'ZonalShiftsInResource' => [ 'type' => 'list', 'member' => [ 'shape' => 'ZonalShiftInResource', ], ], ],];