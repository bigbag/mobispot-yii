BROKER_URL = "redis://localhost:6379/0"

CELERY_RESULT_BACKEND = "redis"

CELERY_IMPORTS = ("tasks", )

CELERY_RESULT_SERIALIZER = "json"
CELERY_TASK_RESULT_EXPIRES = None
