<?php
// This file was auto-generated from sdk-root/src/data/sagemaker-metrics/2024-09-30/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2024-09-30', 'endpointPrefix' => 'metrics.sagemaker', 'protocol' => 'rest-json', 'protocols' => [ 'rest-json', ], 'serviceAbbreviation' => 'SageMaker Metrics', 'serviceFullName' => 'Amazon SageMaker Metrics Service', 'serviceId' => 'SageMaker Metrics', 'signatureVersion' => 'v4', 'signingName' => 'sagemaker', 'uid' => 'sagemaker-metrics-2024-09-30', 'auth' => [ 'aws.auth#sigv4', ], ], 'operations' => [ 'BatchGetMetrics' => [ 'name' => 'BatchGetMetrics', 'http' => [ 'method' => 'POST', 'requestUri' => '/BatchGetMetrics', ], 'input' => [ 'shape' => 'BatchGetMetricsRequest', ], 'output' => [ 'shape' => 'BatchGetMetricsResponse', ], ], 'BatchPutMetrics' => [ 'name' => 'BatchPutMetrics', 'http' => [ 'method' => 'PUT', 'requestUri' => '/BatchPutMetrics', ], 'input' => [ 'shape' => 'BatchPutMetricsRequest', ], 'output' => [ 'shape' => 'BatchPutMetricsResponse', ], ], ], 'shapes' => [ 'BatchGetMetricsRequest' => [ 'type' => 'structure', 'required' => [ 'MetricQueries', ], 'members' => [ 'MetricQueries' => [ 'shape' => 'MetricQueryList', ], ], ], 'BatchGetMetricsResponse' => [ 'type' => 'structure', 'members' => [ 'MetricQueryResults' => [ 'shape' => 'MetricQueryResultList', ], ], ], 'BatchPutMetricsError' => [ 'type' => 'structure', 'members' => [ 'Code' => [ 'shape' => 'PutMetricsErrorCode', ], 'MetricIndex' => [ 'shape' => 'Integer', ], ], ], 'BatchPutMetricsErrorList' => [ 'type' => 'list', 'member' => [ 'shape' => 'BatchPutMetricsError', ], 'max' => 10, 'min' => 1, ], 'BatchPutMetricsRequest' => [ 'type' => 'structure', 'required' => [ 'TrialComponentName', 'MetricData', ], 'members' => [ 'TrialComponentName' => [ 'shape' => 'ExperimentEntityName', ], 'MetricData' => [ 'shape' => 'RawMetricDataList', ], ], ], 'BatchPutMetricsResponse' => [ 'type' => 'structure', 'members' => [ 'Errors' => [ 'shape' => 'BatchPutMetricsErrorList', ], ], ], 'Double' => [ 'type' => 'double', ], 'ExperimentEntityName' => [ 'type' => 'string', 'max' => 120, 'min' => 1, 'pattern' => '^[a-z0-9](-*[a-z0-9]){0,119}', ], 'Integer' => [ 'type' => 'integer', ], 'Long' => [ 'type' => 'long', ], 'Message' => [ 'type' => 'string', 'max' => 2048, 'pattern' => '.*', ], 'MetricName' => [ 'type' => 'string', 'max' => 255, 'min' => 1, 'pattern' => '.+', ], 'MetricQuery' => [ 'type' => 'structure', 'required' => [ 'MetricName', 'ResourceArn', 'MetricStat', 'Period', 'XAxisType', ], 'members' => [ 'MetricName' => [ 'shape' => 'MetricName', ], 'ResourceArn' => [ 'shape' => 'SageMakerResourceArn', ], 'MetricStat' => [ 'shape' => 'MetricStatistic', ], 'Period' => [ 'shape' => 'Period', ], 'XAxisType' => [ 'shape' => 'XAxisType', ], 'Start' => [ 'shape' => 'Long', 'box' => true, ], 'End' => [ 'shape' => 'Long', 'box' => true, ], ], ], 'MetricQueryList' => [ 'type' => 'list', 'member' => [ 'shape' => 'MetricQuery', ], 'max' => 100, 'min' => 1, ], 'MetricQueryResult' => [ 'type' => 'structure', 'required' => [ 'Status', 'XAxisValues', 'MetricValues', ], 'members' => [ 'Status' => [ 'shape' => 'MetricQueryResultStatus', ], 'Message' => [ 'shape' => 'Message', ], 'XAxisValues' => [ 'shape' => 'XAxisValues', ], 'MetricValues' => [ 'shape' => 'MetricValues', ], ], ], 'MetricQueryResultList' => [ 'type' => 'list', 'member' => [ 'shape' => 'MetricQueryResult', ], 'max' => 100, 'min' => 1, ], 'MetricQueryResultStatus' => [ 'type' => 'string', 'enum' => [ 'Complete', 'Truncated', 'InternalError', 'ValidationError', ], ], 'MetricStatistic' => [ 'type' => 'string', 'enum' => [ 'Min', 'Max', 'Avg', 'Count', 'StdDev', 'Last', ], ], 'MetricValues' => [ 'type' => 'list', 'member' => [ 'shape' => 'Double', ], ], 'Period' => [ 'type' => 'string', 'enum' => [ 'OneMinute', 'FiveMinute', 'OneHour', 'IterationNumber', ], ], 'PutMetricsErrorCode' => [ 'type' => 'string', 'enum' => [ 'METRIC_LIMIT_EXCEEDED', 'INTERNAL_ERROR', 'VALIDATION_ERROR', 'CONFLICT_ERROR', ], ], 'RawMetricData' => [ 'type' => 'structure', 'required' => [ 'MetricName', 'Timestamp', 'Value', ], 'members' => [ 'MetricName' => [ 'shape' => 'MetricName', ], 'Timestamp' => [ 'shape' => 'Timestamp', ], 'Step' => [ 'shape' => 'Step', ], 'Value' => [ 'shape' => 'Double', ], ], ], 'RawMetricDataList' => [ 'type' => 'list', 'member' => [ 'shape' => 'RawMetricData', ], 'max' => 10, 'min' => 1, ], 'SageMakerResourceArn' => [ 'type' => 'string', 'max' => 2048, 'pattern' => 'arn:aws[a-z\\-]*:sagemaker:[a-z0-9\\-]*:[0-9]{12}:[a-z\\-].*/.*', ], 'Step' => [ 'type' => 'integer', 'min' => 0, ], 'Timestamp' => [ 'type' => 'timestamp', ], 'XAxisType' => [ 'type' => 'string', 'enum' => [ 'IterationNumber', 'Timestamp', ], ], 'XAxisValues' => [ 'type' => 'list', 'member' => [ 'shape' => 'Long', ], ], ],];
